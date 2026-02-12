<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Report extends Model
{
    /**
     * Report is not backed by a database table.
     * It aggregates data from other models.
     */
    protected $table = null;
    public $timestamps = false;

    // ==================== DATE RANGE HELPERS ====================

    /**
     * Get start and end dates for a given period
     */
    public static function getDateRange(string $period, $dateFrom = null, $dateTo = null): array
    {
        return match($period) {
            'today'      => [Carbon::today(), Carbon::today()->endOfDay()],
            'yesterday'  => [Carbon::yesterday(), Carbon::yesterday()->endOfDay()],
            'this_week'  => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'last_week'  => [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'last_month' => [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ],
            'last_3_months' => [
                Carbon::now()->subMonths(3)->startOfMonth(),
                Carbon::now()->endOfMonth()
            ],
            'last_6_months' => [
                Carbon::now()->subMonths(6)->startOfMonth(),
                Carbon::now()->endOfMonth()
            ],
            'this_year'  => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'last_year'  => [
                Carbon::now()->subYear()->startOfYear(),
                Carbon::now()->subYear()->endOfYear()
            ],
            'custom'     => [
                Carbon::parse($dateFrom ?? now()->startOfMonth()),
                Carbon::parse($dateTo ?? now()),
            ],
            default      => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }

    /**
     * Get the previous period dates for comparison
     */
    public static function getPreviousPeriodRange(Carbon $startDate, Carbon $endDate): array
    {
        $diffDays = $startDate->diffInDays($endDate) + 1;

        return [
            $startDate->copy()->subDays($diffDays),
            $startDate->copy()->subDay(),
        ];
    }

    /**
     * Get a human-readable label for a period
     */
    public static function getPeriodLabel(string $period): string
    {
        return match($period) {
            'today'         => 'Today',
            'yesterday'     => 'Yesterday',
            'this_week'     => 'This Week',
            'last_week'     => 'Last Week',
            'this_month'    => 'This Month',
            'last_month'    => 'Last Month',
            'last_3_months' => 'Last 3 Months',
            'last_6_months' => 'Last 6 Months',
            'this_year'     => 'This Year',
            'last_year'     => 'Last Year',
            'custom'        => 'Custom Range',
            default         => 'This Month',
        };
    }

    // ==================== REVENUE REPORTS ====================

    /**
     * Get total revenue for a period
     */
    public static function getTotalRevenue(Carbon $startDate, Carbon $endDate): float
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['paid', 'partial'])
            ->sum('amount_paid');
    }

    /**
     * Get total outstanding balance for a period
     */
    public static function getTotalOutstanding(Carbon $startDate, Carbon $endDate): float
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'partial')
            ->sum('balance');
    }

    /**
     * Get revenue grouped by day for charts
     */
    public static function getRevenueByDay(Carbon $startDate, Carbon $endDate): array
    {
        $data = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['paid', 'partial'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount_paid) as revenue'),
                DB::raw('SUM(total) as gross'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates
        $result = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dateStr = $current->format('Y-m-d');
            $result[] = [
                'date'    => $dateStr,
                'label'   => $current->format('d M'),
                'revenue' => isset($data[$dateStr]) ? (float) $data[$dateStr]->revenue : 0,
                'gross'   => isset($data[$dateStr]) ? (float) $data[$dateStr]->gross : 0,
                'orders'  => isset($data[$dateStr]) ? (int) $data[$dateStr]->orders : 0,
            ];
            $current->addDay();
        }

        return $result;
    }

    /**
     * Get revenue grouped by month for charts
     */
    public static function getRevenueByMonth(Carbon $startDate, Carbon $endDate): array
    {
        $data = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['paid', 'partial'])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount_paid) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return $data->map(function($item) {
            $date = Carbon::createFromDate($item->year, $item->month, 1);
            return [
                'label'   => $date->format('M Y'),
                'revenue' => (float) $item->revenue,
                'orders'  => (int) $item->orders,
            ];
        })->toArray();
    }

    // ==================== ORDER REPORTS ====================

    /**
     * Get total order count for a period
     */
    public static function getTotalOrders(Carbon $startDate, Carbon $endDate): int
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    /**
     * Get average order value for a period
     */
    public static function getAverageOrderValue(Carbon $startDate, Carbon $endDate): float
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->avg('total') ?? 0;
    }

    /**
     * Get orders grouped by status
     */
    public static function getOrdersByStatus(Carbon $startDate, Carbon $endDate): array
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('status')
            ->get()
            ->toArray();
    }

    /**
     * Get orders grouped by payment status
     */
    public static function getOrdersByPaymentStatus(Carbon $startDate, Carbon $endDate): array
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                'payment_status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total'),
                DB::raw('SUM(amount_paid) as paid'),
                DB::raw('SUM(balance) as balance')
            )
            ->groupBy('payment_status')
            ->get()
            ->toArray();
    }

    // ==================== PAYMENT REPORTS ====================

    /**
     * Get payments grouped by method
     */
    public static function getPaymentsByMethod(Carbon $startDate, Carbon $endDate): array
    {
        return Payment::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->where('status', 'completed')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('payment_method')
            ->get()
            ->toArray();
    }

    /**
     * Get POS vs Online revenue breakdown
     */
    public static function getPOSvsOnlineRevenue(Carbon $startDate, Carbon $endDate): array
    {
        $pos = Order::where('order_number', 'like', 'POS-%')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['paid', 'partial'])
            ->sum('amount_paid');

        $online = Order::where('order_number', 'not like', 'POS-%')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['paid', 'partial'])
            ->sum('amount_paid');

        return [
            'pos'    => (float) $pos,
            'online' => (float) $online,
            'total'  => (float) ($pos + $online),
        ];
    }

    // ==================== PRODUCT REPORTS ====================

    /**
     * Get top selling products
     */
    public static function getTopSellingProducts(
        Carbon $startDate,
        Carbon $endDate,
        int $limit = 10
    ): array {
        return OrderItem::with('product.category')
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as order_count')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get low stock products
     */
    public static function getLowStockProducts(int $threshold = 10): array
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where('quantity', '<=', $threshold)
            ->orderBy('quantity')
            ->get()
            ->toArray();
    }

    /**
     * Get out of stock products
     */
    public static function getOutOfStockProducts(): array
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where('quantity', 0)
            ->get()
            ->toArray();
    }

    /**
     * Get products with no sales in a period
     */
    public static function getDeadStockProducts(Carbon $startDate, Carbon $endDate): array
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->whereDoesntHave('orderItems', function($q) use ($startDate, $endDate) {
                $q->whereHas('order', function($oq) use ($startDate, $endDate) {
                    $oq->whereBetween('created_at', [$startDate, $endDate]);
                });
            })
            ->get()
            ->toArray();
    }

    // ==================== CUSTOMER REPORTS ====================

    /**
     * Get new customers for a period
     */
    public static function getNewCustomers(Carbon $startDate, Carbon $endDate): int
    {
        return User::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    /**
     * Get top spending customers
     */
    public static function getTopCustomers(
        Carbon $startDate,
        Carbon $endDate,
        int $limit = 10
    ): array {
        return Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['paid', 'partial'])
            ->select(
                'user_id',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as total_spent'),
                DB::raw('SUM(amount_paid) as total_paid')
            )
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    // ==================== COUPON REPORTS ====================

    /**
     * Get coupon usage stats
     */
    public static function getCouponStats(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_coupons' => Coupon::count(),
            'active_coupons' => Coupon::where('is_active', true)->count(),
            'expired_coupons' => Coupon::where('valid_until', '<', now())->count(),
            'total_uses' => Coupon::sum('used_count'),
        ];
    }

    // ==================== SUMMARY REPORT ====================

    /**
     * Get complete summary for a period
     */
    public static function getSummary(Carbon $startDate, Carbon $endDate): array
    {
        [$prevStart, $prevEnd] = self::getPreviousPeriodRange($startDate, $endDate);

        $currentRevenue  = self::getTotalRevenue($startDate, $endDate);
        $previousRevenue = self::getTotalRevenue($prevStart, $prevEnd);

        $currentOrders  = self::getTotalOrders($startDate, $endDate);
        $previousOrders = self::getTotalOrders($prevStart, $prevEnd);

        $currentCustomers  = self::getNewCustomers($startDate, $endDate);
        $previousCustomers = self::getNewCustomers($prevStart, $prevEnd);

        return [
            // Current period
            'total_revenue'       => $currentRevenue,
            'total_orders'        => $currentOrders,
            'new_customers'       => $currentCustomers,
            'avg_order_value'     => self::getAverageOrderValue($startDate, $endDate),
            'outstanding_balance' => self::getTotalOutstanding($startDate, $endDate),

            // POS vs Online
            'pos_vs_online' => self::getPOSvsOnlineRevenue($startDate, $endDate),

            // Growth comparisons
            'revenue_growth'   => self::calculateGrowth($currentRevenue, $previousRevenue),
            'orders_growth'    => self::calculateGrowth($currentOrders, $previousOrders),
            'customers_growth' => self::calculateGrowth($currentCustomers, $previousCustomers),

            // Previous period
            'prev_revenue' => $previousRevenue,
            'prev_orders'  => $previousOrders,
        ];
    }

    /**
     * Calculate growth percentage between two values
     */
    public static function calculateGrowth(float|int $current, float|int $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    // ==================== STOCK REPORTS ====================

    /**
     * Get stock valuation
     */
    public static function getStockValuation(): array
    {
        $products = Product::where('is_active', true)
            ->select(
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(quantity) as total_units'),
                DB::raw('SUM(quantity * price) as total_value')
            )
            ->first();

        return [
            'total_products' => $products->total_products ?? 0,
            'total_units'    => $products->total_units ?? 0,
            'total_value'    => $products->total_value ?? 0,
        ];
    }

    /**
     * Get stock movement for a period (sold vs restocked)
     */
    public static function getStockMovement(Carbon $startDate, Carbon $endDate): array
    {
        $sold = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('quantity');

        return [
            'sold'      => (int) $sold,
            'by_category' => OrderItem::with('product.category')
                ->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->select(
                    'product_id',
                    DB::raw('SUM(quantity) as qty_sold')
                )
                ->groupBy('product_id')
                ->get()
                ->groupBy('product.category.name')
                ->map(fn($items) => $items->sum('qty_sold'))
                ->toArray(),
        ];
    }
}