@extends('admin.layouts.app')
@section('title', 'Reports & Analytics')

@push('plugin-styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h5 class="mb-0">₦{{ number_format($summary['total_revenue'], 2) }}</h5>
                    @if($summary['revenue_growth'] != 0)
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
                    <h5 class="mb-0">{{ number_format($summary['total_orders']) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-info">
                <div class="card-body">
                    <p class="text-muted small mb-1">New Customers</p>
                    <h5 class="mb-0">{{ number_format($summary['total_customers']) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-warning">
                <div class="card-body">
                    <p class="text-muted small mb-1">Avg Order Value</p>
                    <h5 class="mb-0">₦{{ number_format($summary['avg_order_value'], 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-danger">
                <div class="card-body">
                    <p class="text-muted small mb-1">Outstanding</p>
                    <h5 class="mb-0">₦{{ number_format($summary['outstanding_balance'], 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-dark">
                <div class="card-body">
                    <p class="text-muted small mb-1">POS Revenue</p>
                    <h5 class="mb-0">₦{{ number_format($summary['pos_revenue'], 2) }}</h5>
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
                    <canvas id="paymentChart" height="200"></canvas>
                    <div class="mt-3">
                        @foreach($paymentMethods as $pm)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-capitalize">{{ $pm->payment_method }}</span>
                                <div>
                                    <span class="badge badge-primary mr-1">{{ $pm->count }}x</span>
                                    <strong>₦{{ number_format($pm->total, 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add these sections after the existing charts in the view -->

        <!-- Top Customers -->
        <div class="row mt-4">
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
                                    @forelse($topCustomers as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['user']['name'] ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $item['order_count'] }}
                                                </span>
                                            </td>
                                            <td>₦{{ number_format($item['total_spent'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No data</td>
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
                        <canvas id="posOnlineChart" height="200"></canvas>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>POS Sales:</span>
                                <strong>₦{{ number_format($summary['pos_vs_online']['pos'], 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Online Sales:</span>
                                <strong>₦{{ number_format($summary['pos_vs_online']['online'], 2) }}</strong>
                            </div>
                        </div>
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
                            <h4>{{ number_format($stockValuation['total_products']) }}</h4>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Total Units</p>
                            <h4>{{ number_format($stockValuation['total_units']) }}</h4>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Stock Value</p>
                            <h4 class="text-success">
                                ₦{{ number_format($stockValuation['total_value'], 2) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        @if(count($lowStock) > 0)
        <div class="row mt-4">
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
                                            <td>{{ $product['name'] }}</td>
                                            <td>{{ $product['sku'] }}</td>
                                            <td>{{ $product['category']['name'] ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $product['quantity'] == 0 ? 'danger' : 'warning' }}">
                                                    {{ $product['quantity'] }} units
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/admin/products/{{ $product['id'] }}/edit"
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
        
    
    <div class="row">
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
                                @forelse($topProducts as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $item->total_quantity }}</span>
                                        </td>
                                        <td>₦{{ number_format($item->total_revenue, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No data</td>
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
                    <canvas id="statusChart" height="220"></canvas>
                    <div class="mt-3">
                        @foreach($orderStatus as $status)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-capitalize">{{ $status->status }}</span>
                                <div>
                                    <span class="badge badge-secondary mr-1">{{ $status->count }}</span>
                                    <strong>₦{{ number_format($status->total, 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    // Revenue Chart
    const revenueData = @json($revenueData);
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

    // Payment Methods Chart
    const paymentData = @json($paymentMethods);
    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: paymentData.map(p => p.payment_method.toUpperCase()),
            datasets: [{
                data: paymentData.map(p => parseFloat(p.total)),
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

    // Order Status Chart
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
            labels: statusData.map(s => s.status.charAt(0).toUpperCase() + s.status.slice(1)),
            datasets: [{
                data: statusData.map(s => s.count),
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
    
    
    // Add to existing @push('custom-scripts')
    new Chart(document.getElementById('posOnlineChart'), {
        type: 'doughnut',
        data: {
            labels: ['POS Sales', 'Online Sales'],
            datasets: [{
                data: [
                    {{ $summary['pos_vs_online']['pos'] }},
                    {{ $summary['pos_vs_online']['online'] }}
                ],
                backgroundColor: ['#4e73df', '#1cc88a'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
    
    function toggleCustomDates() {
        const isCustom = document.querySelector('input[name="period"]:checked').value === 'custom';
        document.getElementById('custom-dates').style.display = isCustom ? '' : 'none';
    }
    
    
</script>
@endpush