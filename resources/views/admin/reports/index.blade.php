@extends('admin.layouts.app')
@section('title', 'Reports & Analytics')

@push('plugin-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Reports & Analytics</h4>
                <a href="{{ route('admin.reports.export', ['period' => $period]) }}"
                   class="btn btn-success">
                    <i class="mdi mdi-file-excel"></i> Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.index') }}" id="period-form">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label>Select Period</label>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach([
                                'today' => 'Today',
                                'yesterday' => 'Yesterday',
                                'this_week' => 'This Week',
                                'last_week' => 'Last Week',
                                'this_month' => 'This Month',
                                'last_month' => 'Last Month',
                                'this_year' => 'This Year',
                                'custom' => 'Custom',
                            ] as $value => $label)
                                <label class="btn btn-outline-primary {{ $period === $value ? 'active' : '' }}">
                                    <input type="radio" name="period" value="{{ $value }}"
                                           {{ $period === $value ? 'checked' : '' }}
                                           onchange="toggleCustomDates()"> {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4" id="custom-dates"
                         style="{{ $period === 'custom' ? '' : 'display:none;' }}">
                        <div class="row">
                            <div class="col-6">
                                <input type="date" class="form-control" name="date_from"
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" name="date_to"
                                       value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="mdi mdi-filter"></i> Apply
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-left-success">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h5 class="mb-0">₦{{ number_format($summary['total_revenue'] ?? 0, 2) }}</h5>
                    @if(isset($summary['revenue_growth']) && $summary['revenue_growth'] != 0)
                        <small class="text-{{ $summary['revenue_growth'] > 0 ? 'success' : 'danger' }}">
                            <i class="mdi mdi-{{ $summary['revenue_growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($summary['revenue_growth']) }}% vs prev.
                        </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-primary">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Orders</p>
                    <h5 class="mb-0">{{ number_format($summary['total_orders'] ?? 0) }}</h5>
                    @if(isset($summary['orders_growth']) && $summary['orders_growth'] != 0)
                        <small class="text-{{ $summary['orders_growth'] > 0 ? 'success' : 'danger' }}">
                            <i class="mdi mdi-{{ $summary['orders_growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($summary['orders_growth']) }}%
                        </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-info">
                <div class="card-body">
                    <p class="text-muted small mb-1">New Customers</p>
                    <h5 class="mb-0">{{ number_format($summary['total_customers'] ?? 0) }}</h5>
                    @if(isset($summary['customers_growth']) && $summary['customers_growth'] != 0)
                        <small class="text-{{ $summary['customers_growth'] > 0 ? 'success' : 'danger' }}">
                            <i class="mdi mdi-{{ $summary['customers_growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($summary['customers_growth']) }}%
                        </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-warning">
                <div class="card-body">
                    <p class="text-muted small mb-1">Avg Order Value</p>
                    <h5 class="mb-0">₦{{ number_format($summary['avg_order_value'] ?? 0, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-danger">
                <div class="card-body">
                    <p class="text-muted small mb-1">Outstanding</p>
                    <h5 class="mb-0">₦{{ number_format($summary['outstanding_balance'] ?? 0, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-dark">
                <div class="card-body">
                    <p class="text-muted small mb-1">POS Revenue</p>
                    <h5 class="mb-0">₦{{ number_format($summary['pos_revenue'] ?? 0, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Revenue & Orders Chart</h4>
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payment Methods</h4>
                    @if(count($paymentMethods ?? []) > 0)
                        <canvas id="paymentChart" height="200"></canvas>
                        <div class="mt-3">
                            @foreach($paymentMethods as $pm)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-capitalize">{{ $pm['payment_method'] ?? 'N/A' }}</span>
                                    <div>
                                        <span class="badge badge-primary mr-1">{{ $pm['count'] ?? 0 }}x</span>
                                        <strong>₦{{ number_format($pm['total'] ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted py-5">No payment data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Top Products, Order Status -->
    <div class="row mb-4">
        <!-- Top Products -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Top Selling Products</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Qty Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts ?? [] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['product']['name'] ?? 'Deleted Product' }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $item['total_quantity'] ?? 0 }}
                                            </span>
                                        </td>
                                        <td>₦{{ number_format($item['total_revenue'] ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No sales data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Status Breakdown</h4>
                    @if(count($orderStatus ?? []) > 0)
                        <canvas id="statusChart" height="220"></canvas>
                        <div class="mt-3">
                            @foreach($orderStatus as $status)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-capitalize">{{ $status['status'] ?? 'N/A' }}</span>
                                    <div>
                                        <span class="badge badge-secondary mr-1">{{ $status['count'] ?? 0 }}</span>
                                        <strong>₦{{ number_format($status['total'] ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted py-5">No order data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Third Row: Top Customers, POS vs Online, Stock Valuation -->
    <div class="row mb-4">
        <!-- Top Customers -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Top Customers</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Orders</th>
                                    <th>Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers ?? [] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['user']['name'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $item['order_count'] ?? 0 }}
                                            </span>
                                        </td>
                                        <td>₦{{ number_format($item['total_spent'] ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No customer data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- POS vs Online -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">POS vs Online</h4>
                    @if(isset($summary['pos_vs_online']))
                        <canvas id="posOnlineChart" height="200"></canvas>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>POS Sales:</span>
                                <strong>₦{{ number_format($summary['pos_vs_online']['pos'] ?? 0, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Online Sales:</span>
                                <strong>₦{{ number_format($summary['pos_vs_online']['online'] ?? 0, 2) }}</strong>
                            </div>
                        </div>
                    @else
                        <p class="text-center text-muted py-5">No data</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stock Valuation -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Stock Valuation</h4>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Total Products</p>
                        <h4>{{ number_format($stockValuation['total_products'] ?? 0) }}</h4>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Total Units</p>
                        <h4>{{ number_format($stockValuation['total_units'] ?? 0) }}</h4>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Stock Value</p>
                        <h4 class="text-success">
                            ₦{{ number_format($stockValuation['total_value'] ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    @if(count($lowStock ?? []) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card border-left-warning">
                <div class="card-body">
                    <h4 class="card-title text-warning">
                        <i class="mdi mdi-alert"></i>
                        Low Stock Alert ({{ count($lowStock) }} products)
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStock as $product)
                                    <tr>
                                        <td>{{ $product['name'] ?? 'N/A' }}</td>
                                        <td>{{ $product['sku'] ?? 'N/A' }}</td>
                                        <td>{{ $product['category']['name'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ ($product['quantity'] ?? 0) == 0 ? 'danger' : 'warning' }}">
                                                {{ $product['quantity'] ?? 0 }} units
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $product['id']) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="mdi mdi-pencil"></i> Update Stock
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('custom-scripts')
<script>
    // ==================== REVENUE CHART ====================
    const revenueData = @json($revenueData ?? []);
    const labels = revenueData.map(d => d.label);
    const revenues = revenueData.map(d => parseFloat(d.revenue));
    const orders = revenueData.map(d => parseInt(d.orders));

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenue (₦)',
                    data: revenues,
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1,
                    yAxisID: 'y',
                },
                {
                    label: 'Orders',
                    data: orders,
                    type: 'line',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    fill: true,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    position: 'left',
                    ticks: {
                        callback: value => '₦' + value.toLocaleString()
                    }
                },
                y1: {
                    position: 'right',
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // ==================== PAYMENT METHODS CHART ====================
    @if(count($paymentMethods ?? []) > 0)
    const paymentData = @json($paymentMethods);
    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: paymentData.map(p => (p.payment_method || 'N/A').toUpperCase()),
            datasets: [{
                data: paymentData.map(p => parseFloat(p.total || 0)),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    @endif

    // ==================== ORDER STATUS CHART ====================
    @if(count($orderStatus ?? []) > 0)
    const statusData = @json($orderStatus);
    const statusColors = {
        pending: '#f6c23e',
        processing: '#36b9cc',
        shipped: '#4e73df',
        delivered: '#1cc88a',
        cancelled: '#e74a3b'
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusData.map(s => (s.status || 'N/A').charAt(0).toUpperCase() + (s.status || 'N/A').slice(1)),
            datasets: [{
                data: statusData.map(s => parseInt(s.count || 0)),
                backgroundColor: statusData.map(s => statusColors[s.status] || '#858796'),
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    @endif

    // ==================== POS VS ONLINE CHART ====================
    @if(isset($summary['pos_vs_online']))
    const posValue = {{ $summary['pos_vs_online']['pos'] ?? 0 }};
    const onlineValue = {{ $summary['pos_vs_online']['online'] ?? 0 }};

    // Only create chart if there's data
    if (posValue > 0 || onlineValue > 0) {
        new Chart(document.getElementById('posOnlineChart'), {
            type: 'doughnut',
            data: {
                labels: ['POS Sales', 'Online Sales'],
                datasets: [{
                    data: [posValue, onlineValue],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
    @endif

    // ==================== HELPER FUNCTIONS ====================
    function toggleCustomDates() {
        const isCustom = document.querySelector('input[name="period"]:checked').value === 'custom';
        document.getElementById('custom-dates').style.display = isCustom ? '' : 'none';
    }
</script>
@endpush