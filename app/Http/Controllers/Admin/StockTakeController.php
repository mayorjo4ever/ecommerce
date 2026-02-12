<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockTake;
use App\Models\StockTakeItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockTakeController extends Controller
{
    public function index()
    {
        $stockTakes = StockTake::with('createdBy')
            ->latest()
            ->paginate(15);

        return view('admin.stock-takes.index', compact('stockTakes'));
    }

    public function create()
    {
        $productCount = Product::where('is_active', true)->count();
        return view('admin.stock-takes.create', compact('productCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:weekly,monthly,yearly,custom',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create stock take
            $stockTake = StockTake::create([
                'reference' => 'STK-' . strtoupper(uniqid()),
                'type' => $validated['type'],
                'status' => 'in_progress',
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'notes' => $validated['notes'],
                'created_by' => auth()->guard('admin')->id(),
            ]);

            // Add all active products to stock take
            $products = Product::where('is_active', true)->get();

            foreach ($products as $product) {
                StockTakeItem::create([
                    'stock_take_id' => $stockTake->id,
                    'product_id' => $product->id,
                    'system_quantity' => $product->quantity,
                    'physical_quantity' => null,
                    'variance' => null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.stock-takes.show', $stockTake->id)
                ->with('success', 'Stock take initiated! Please count and enter physical quantities.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create stock take: ' . $e->getMessage()]);
        }
    }

    public function show(StockTake $stockTake)
    {
        $stockTake->load(['items.product.category', 'createdBy', 'completedBy']);

        $items = $stockTake->items;
        $counted = $items->whereNotNull('physical_quantity')->count();
        $total = $items->count();
        $progress = $total > 0 ? round(($counted / $total) * 100) : 0;

        return view('admin.stock-takes.show', compact('stockTake', 'progress', 'counted', 'total'));
    }

    public function updateItem(Request $request, StockTake $stockTake, StockTakeItem $item)
    {
        $validated = $request->validate([
            'physical_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $variance = $validated['physical_quantity'] - $item->system_quantity;

        $item->update([
            'physical_quantity' => $validated['physical_quantity'],
            'variance' => $variance,
            'notes' => $validated['notes'],
        ]);

        return response()->json([
            'success' => true,
            'variance' => $variance,
            'message' => $variance == 0 
                ? 'No discrepancy' 
                : ($variance > 0 ? '+' . $variance . ' excess' : $variance . ' shortage'),
        ]);
    }

    public function complete(StockTake $stockTake)
    {
        // Check if all items are counted
        $uncounted = $stockTake->items()->whereNull('physical_quantity')->count();

        if ($uncounted > 0) {
            return back()->withErrors([
                'error' => "{$uncounted} products still need to be counted."
            ]);
        }

        DB::beginTransaction();

        try {
            // Update product quantities based on physical count
            foreach ($stockTake->items as $item) {
                if ($item->physical_quantity !== null && $item->physical_quantity !== $item->system_quantity) {
                    $item->product->update(['quantity' => $item->physical_quantity]);
                }
            }

            // Mark stock take as completed
            $stockTake->update([
                'status' => 'completed',
                'completed_by' => auth()->guard('admin')->id(),
                'completed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.stock-takes.show', $stockTake->id)
                ->with('success', 'Stock take completed! Product quantities have been updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to complete stock take: ' . $e->getMessage()]);
        }
    }

    public function export(StockTake $stockTake)
    {
        $stockTake->load(['items.product.category', 'createdBy']);

        $filename = 'stock_take_' . $stockTake->reference . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($stockTake) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Stock Take Reference:', $stockTake->reference
            ]);
            fputcsv($file, ['Period:', $stockTake->period_start->format('d M Y') . ' - ' . $stockTake->period_end->format('d M Y')]);
            fputcsv($file, ['Status:', ucfirst($stockTake->status)]);
            fputcsv($file, []);

            fputcsv($file, [
                'SKU', 'Product Name', 'Category',
                'System Qty', 'Physical Qty', 'Variance', 'Notes'
            ]);

            foreach ($stockTake->items as $item) {
                fputcsv($file, [
                    $item->product->sku ?? 'N/A',
                    $item->product->name ?? 'Deleted Product',
                    $item->product->category->name ?? 'N/A',
                    $item->system_quantity,
                    $item->physical_quantity ?? 'Not counted',
                    $item->variance ?? 'N/A',
                    $item->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(StockTake $stockTake)
    {
        if ($stockTake->status === 'completed') {
            return back()->withErrors(['error' => 'Cannot delete a completed stock take.']);
        }

        $stockTake->delete();

        return redirect()->route('admin.stock-takes.index')
            ->with('success', 'Stock take deleted successfully!');
    }
}