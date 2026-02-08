<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total counts
        $totalOrders = Order::count();
        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = User::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total');

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('quantity', '<', 10)
            ->where('is_active', true)
            ->orderBy('quantity', 'asc')
            ->take(10)
            ->get();

        // Order status counts
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders'
        ));
    }
}