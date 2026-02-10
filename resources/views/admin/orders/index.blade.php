@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">All Orders</h4>
                <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> New POS Sale
                </a>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    @include('admin.components.date-filter', [
        'action' => route('admin.orders.index'),
        'exportRoute' => route('admin.orders.export'),
        'extraLabel' => 'Order Status',
        'extraName' => 'status',
        'extraOptions' => [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ]
    ])

    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by order number or customer name...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="mdi mdi-magnify"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Orders</p>
                            <h4 class="mb-0">{{ number_format($summary['total_orders']) }}</h4>
                        </div>
                        <i class="mdi mdi-shopping mdi-36px text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Revenue</p>
                            <h4 class="mb-0">₦{{ number_format($summary['total_revenue'], 2) }}</h4>
                        </div>
                        <i class="mdi mdi-cash mdi-36px text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Paid</p>
                            <h4 class="mb-0">₦{{ number_format($summary['total_paid'], 2) }}</h4>
                        </div>
                        <i class="mdi mdi-check-circle mdi-36px text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Outstanding Balance</p>
                            <h4 class="mb-0">₦{{ number_format($summary['total_balance'], 2) }}</h4>
                        </div>
                        <i class="mdi mdi-alert mdi-36px text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Filters Display -->
    @if(request()->hasAny(['date_from', 'date_to', 'status', 'search']))
        <div class="alert alert-info alert-dismissible fade show">
            <strong>Active Filters:</strong>
            @if(request('date_from'))
                <span class="badge badge-primary ml-2">From: {{ request('date_from') }}</span>
            @endif
            @if(request('date_to'))
                <span class="badge badge-primary ml-2">To: {{ request('date_to') }}</span>
            @endif
            @if(request('status'))
                <span class="badge badge-info ml-2">Status: {{ ucfirst(request('status')) }}</span>
            @endif
            @if(request('search'))
                <span class="badge badge-secondary ml-2">Search: {{ request('search') }}</span>
            @endif
            <a href="{{ route('admin.orders.index') }}" class="ml-3 text-white">
                <i class="mdi mdi-close-circle"></i> Clear All Filters
            </a>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">
                        Orders 
                        <span class="badge badge-primary ml-2">{{ $summary['total_orders'] }}</span>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <small class="font-weight-bold">{{ $order->order_number }}</small>
                                        </td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>₦{{ number_format($order->total, 2) }}</td>
                                        <td class="text-success">
                                            ₦{{ number_format($order->amount_paid, 2) }}
                                        </td>
                                        <td>
                                            @if($order->balance > 0)
                                                <span class="text-danger font-weight-bold">
                                                    ₦{{ number_format($order->balance, 2) }}
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="mdi mdi-check"></i> Cleared
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $order->status_color }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $order->payment_status_color }}">
                                                {{ ucfirst($order->payment_status ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $order->created_at->format('d M, Y') }}</small><br>
                                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pos.receipt', $order->id) }}"
                                               class="btn btn-sm btn-primary" target="_blank" title="Print">
                                                <i class="mdi mdi-printer"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="mdi mdi-shopping mdi-48px text-muted"></i>
                                            <p class="text-muted mt-2">No orders found for selected filters</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script src="{{ asset('admin/js/date-filter.js') }}"></script>
@endpush