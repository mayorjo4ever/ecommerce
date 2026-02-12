<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get period and date range
        $period = $request->get('period', 'this_month');

        [$startDate, $endDate] = Report::getDateRange(
            $period,
            $request->date_from,
            $request->date_to
        );

        // Use Report model methods
        $summary        = Report::getSummary($startDate, $endDate);
        $revenueData    = Report::getRevenueByDay($startDate, $endDate);
        $topProducts    = Report::getTopSellingProducts($startDate, $endDate, 10);
        $paymentMethods = Report::getPaymentsByMethod($startDate, $endDate);
        $orderStatus    = Report::getOrdersByStatus($startDate, $endDate);
        $topCustomers   = Report::getTopCustomers($startDate, $endDate, 5);
        $stockValuation = Report::getStockValuation();
        $lowStock       = Report::getLowStockProducts(10);
        $periodLabel    = Report::getPeriodLabel($period);

        return view('admin.reports.index', compact(
            'summary', 'revenueData', 'topProducts',
            'paymentMethods', 'orderStatus', 'topCustomers',
            'stockValuation', 'lowStock', 'period', 'periodLabel',
            'startDate', 'endDate'
        ));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'this_month');

        [$startDate, $endDate] = Report::getDateRange(
            $period,
            $request->date_from,
            $request->date_to
        );

        $orders = Order::with(['user', 'payments', 'orderItems.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $filename = 'report_' . $period . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($orders, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            // Report header
            fputcsv($file, ['Report Period:', $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')]);
            fputcsv($file, ['Generated:', now()->format('d M Y H:i')]);
            fputcsv($file, []);

            // Column headers
            fputcsv($file, [
                'Order Number', 'Customer', 'Items',
                'Subtotal', 'Discount', 'Tax', 'Total',
                'Amount Paid', 'Balance', 'Payment Status',
                'Order Status', 'Payment Methods', 'Date'
            ]);

            foreach ($orders as $order) {
                $methods = $order->payments->map(fn($p) =>
                    strtoupper($p->payment_method) . ':₦' . number_format($p->amount, 2)
                )->implode(' | ');

                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'N/A',
                    $order->orderItems->sum('quantity'),
                    $order->subtotal,
                    $order->discount,
                    $order->tax,
                    $order->total,
                    $order->amount_paid,
                    $order->balance,
                    ucfirst($order->payment_status),
                    ucfirst($order->status),
                    $methods,
                    $order->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}