<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\DateFilterTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    use DateFilterTrait;

    public function index(Request $request)
    {
        $query = Order::with(['user', 'payments'])
            ->latest();

        // Apply date filter
        $this->applyDateFilter($query, $request);

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Get summary before pagination
        $summaryQuery = clone $query;
        $summary = [
            'total_orders' => $summaryQuery->count(),
            'total_revenue' => $summaryQuery->sum('total'),
            'total_paid' => $summaryQuery->sum('amount_paid'),
            'total_balance' => $summaryQuery->sum('balance'),
        ];

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders', 'summary'));
    }

    public function show(Order $order)
    {
        $order->load([
            'orderItems.product.category',
            'user',
            'payments',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order status updated successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = Order::with(['user', 'payments'])->latest();

        $this->applyDateFilter($query, $request);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->get();

        $filename = 'orders_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Subtotal',
                'Discount',
                'Tax',
                'Total',
                'Amount Paid',
                'Balance',
                'Status',
                'Payment Status',
                'Date',
            ]);

            // CSV Rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'N/A',
                    $order->user->email ?? 'N/A',
                    $order->subtotal,
                    $order->discount,
                    $order->tax,
                    $order->total,
                    $order->amount_paid,
                    $order->balance,
                    ucfirst($order->status),
                    ucfirst($order->payment_status),
                    $order->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}