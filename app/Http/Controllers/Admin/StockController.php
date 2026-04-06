<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Show stock movement history for a specific product
     */
    public function history(Product $product)
    {
        $movements = StockMovement::where('product_id', $product->id)
            ->with(['createdBy'])
            ->latest()
            ->paginate(20);

        $summary = [
            'total_in'       => StockMovement::where('product_id', $product->id)
                                    ->whereIn('type', ['in', 'return'])
                                    ->sum('quantity'),
            'total_out'      => StockMovement::where('product_id', $product->id)
                                    ->whereIn('type', ['out', 'damaged'])
                                    ->sum('quantity'),
            'total_damaged'  => StockMovement::where('product_id', $product->id)
                                    ->where('type', 'damaged')
                                    ->sum('quantity'),
            'movements_count' => StockMovement::where('product_id', $product->id)->count(),
        ];

        return view('admin.stock.history', compact('product', 'movements', 'summary'));
    }

    /**
     * Show the add stock form
     */
    public function create(Product $product)
    {
        return view('admin.stock.create', compact('product'));
    }

    /**
     * Store a new stock movement and update the product quantity
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type'          => 'required|in:in,out,adjustment,return,damaged',
            'quantity'      => 'required|integer|min:1',
            'cost_price'    => 'nullable|numeric|min:0',
            'supplier_name' => 'nullable|string|max:150',
            'reference_no'  => 'nullable|string|max:100',
            'note'          => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $product) {
            $quantityBefore = $product->quantity;

            // Calculate new stock based on movement type
            if (in_array($validated['type'], ['in', 'return'])) {
                $newQuantity = $quantityBefore + $validated['quantity'];
            } else {
                // out, damaged, adjustment — reduce stock
                $newQuantity = max(0, $quantityBefore - $validated['quantity']);
            }

            // Record the movement
            StockMovement::create([
                'product_id'      => $product->id,
                'supplier_name'   => $validated['supplier_name'] ?? null,
                'created_by'      => Auth::guard('admin')->id(),
                'type'            => $validated['type'],
                'quantity'        => $validated['quantity'],
                'quantity_before' => $quantityBefore,
                'quantity_after'  => $newQuantity,
                'cost_price'      => $validated['cost_price'] ?? null,
                'reference_no'    => $validated['reference_no'] ?? null,
                'note'            => $validated['note'] ?? null,
            ]);

            // Update the product quantity
            $product->update(['quantity' => $newQuantity]);
        });

        return redirect()->route('admin.stock.history', $product)
            ->with('success', 'Stock movement recorded successfully!');
    }

    /**
     * Global stock movements report (all products)
     */
    public function report(Request $request)
    {
        $query = StockMovement::with(['product', 'createdBy'])->latest();

        // Filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $movements = $query->paginate(25)->withQueryString();
        $products  = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.stock.report', compact('movements', 'products'));
    }
}