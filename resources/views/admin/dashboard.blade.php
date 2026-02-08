@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome {{ auth()->guard('admin')->user()->name }}</h3>
                    <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have 
                        <span class="text-primary">{{ $totalOrders }}</span> orders today!</h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                            <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" 
                                    id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="mdi mdi-calendar"></i> Today ({{ date('d M Y') }})
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card tale-bg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="mb-0 text-white">Total Revenue</p>
                        <i class="mdi mdi-cash-multiple mdi-24px text-white"></i>
                    </div>
                    <p class="fs-30 mb-2 text-white">₦{{ number_format($totalRevenue, 2) }}</p>
                    <p class="mb-0 text-white"><span class="text-success">All time</span></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="font-weight-normal mb-0">Total Orders</h4>
                        <i class="mdi mdi-chart-line mdi-24px"></i>
                    </div>
                    <h2 class="mb-3">{{ $totalOrders }}</h2>
                    <h6 class="card-text">All time orders</h6>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="font-weight-normal mb-0">Total Products</h4>
                        <i class="mdi mdi-package-variant mdi-24px"></i>
                    </div>
                    <h2 class="mb-3">{{ $totalProducts }}</h2>
                    <h6 class="card-text">Active products</h6>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="font-weight-normal mb-0">Total Customers</h4>
                        <i class="mdi mdi-account-multiple mdi-24px"></i>
                    </div>
                    <h2 class="mb-3">{{ $totalCustomers }}</h2>
                    <h6 class="card-text">Registered users</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Low Stock Products -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="card-title">Recent Orders</p>
                        <a href="{{-- route('admin.orders.index') --}}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>₦{{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <label class="badge badge-{{ $order->status_color }}">
                                                {{ ucfirst($order->status) }}
                                            </label>
                                        </td>
                                        <td>{{ $order->created_at->format('d M, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Low Stock Alert</h4>
                        <a href="{{-- route('admin.products.index') --}}" class="btn btn-sm btn-warning">View All</a>
                    </div>
                    <div class="list-wrapper">
                        <ul class="todo-list todo-list-rounded">
                            @forelse($lowStockProducts as $product)
                                <li class="d-block">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            @if($product->featured_image)
                                                <img src="{{ asset('storage/' . $product->featured_image) }}" 
                                                     class="rounded mr-3" 
                                                     style="width: 40px; height: 40px; object-fit: cover;" 
                                                     alt="{{ $product->name }}">
                                            @else
                                                <div class="rounded mr-3 bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="mdi mdi-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                   class="text-dark">
                                                    {{ Str::limit($product->name, 25) }}
                                                </a>
                                                <p class="mb-0 text-muted small">SKU: {{ $product->sku }}</p>
                                            </div>
                                        </div>
                                        <span class="badge badge-{{ $product->quantity == 0 ? 'danger' : 'warning' }}">
                                            {{ $product->quantity }} left
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center">
                                    <p class="text-muted">All products are well stocked!</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Overview -->
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Order Status Overview</p>
                    <div class="row">
                        <div class="col-md-2 col-6">
                            <div class="d-flex flex-column text-center">
                                <p class="mb-2 text-muted">Pending</p>
                                <h3 class="mb-0">{{ $pendingOrders ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="d-flex flex-column text-center">
                                <p class="mb-2 text-muted">Processing</p>
                                <h3 class="mb-0">{{ $processingOrders ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="d-flex flex-column text-center">
                                <p class="mb-2 text-muted">Shipped</p>
                                <h3 class="mb-0">{{ $shippedOrders ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="d-flex flex-column text-center">
                                <p class="mb-2 text-muted">Delivered</p>
                                <h3 class="mb-0">{{ $deliveredOrders ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="d-flex flex-column text-center">
                                <p class="mb-2 text-muted">Cancelled</p>
                                <h3 class="mb-0">{{ $cancelledOrders ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="d-flex flex-column text-center">
                                <p class="mb-2 text-muted">Total</p>
                                <h3 class="mb-0">{{ $totalOrders }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Quick Actions</h4>
                    <div class="row">
                        @can('create products')
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-block btn-lg btn-primary">
                                <i class="mdi mdi-plus-circle mr-2"></i> Add Product
                            </a>
                        </div>
                        @endcan

                        @can('create categories')
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-block btn-lg btn-info">
                                <i class="mdi mdi-folder-plus mr-2"></i> Add Category
                            </a>
                        </div>
                        @endcan

                        @can('view orders')
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-block btn-lg btn-warning">
                                <i class="mdi mdi-format-list-bulleted mr-2"></i> View Orders
                            </a>
                        </div>
                        @endcan

                        @can('create coupons')
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.coupons.create') }}" class="btn btn-block btn-lg btn-success">
                                <i class="mdi mdi-ticket-percent mr-2"></i> Create Coupon
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        // You can add custom JavaScript here for charts or interactive elements
        console.log('Dashboard loaded');
    </script>
@endpush