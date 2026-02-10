@extends('admin.layouts.app')

@section('title', 'POS Sales History')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">POS Sales History</h4>
                    <p class="text-muted">View all point of sale transactions</p>
                </div>
                <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
                    <i class="mdi mdi-point-of-sale"></i> New Sale
                </a>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    @include('admin.components.date-filter', [
        'action' => route('admin.pos.history'),
        'exportRoute' => route('admin.pos.export'),
        'extraLabel' => 'Payment Status',
        'extraName' => 'payment_status',
        'extraOptions' => [
            'paid' => 'Fully Paid',
            'partial' => 'Partial Payment',
            'unpaid' => 'Unpaid',
        ]
    ])

    <!-- Payment Method Filter -->
    <div class="card mb-4">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.pos.history') }}" class="row align-items-end">
                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
                <div class="col-md-4">
                    <select class="form-control" name="payment_method">
                        <option value="">All Payment Methods</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                        <option value="pos" {{ request('payment_method') === 'pos' ? 'selected' : '' }}>POS</option>
                        <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by order number or customer...">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="mdi mdi-magnify"></i> Search
                    </button>
                    <a href="{{ route('admin.pos.history') }}" class="btn btn-secondary">
                        <i class="mdi mdi-refresh"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-left-primary">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Sales</p>
                    <h4 class="mb-0">{{ number_format($summary['total_sales']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-success">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Revenue</p>
                    <h5 class="mb-0">₦{{ number_format($summary['total_revenue'], 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-info">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Paid</p>
                    <h5 class="mb-0">₦{{ number_format($summary['total_paid'], 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-warning">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Outstanding</p>
                    <h5 class="mb-0">₦{{ number_format($summary['total_balance'], 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-dark">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Cash Sales</p>
                    <h5 class="mb-0">₦{{ number_format($summary['cash_sales'], 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-secondary">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Transfer Sales</p>
                    <h5 class="mb-0">₦{{ number_format($summary['transfer_sales'], 2) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Filters Display -->
    @if(request()->hasAny(['date_from', 'date_to', 'payment_status', 'payment_method', 'search']))
        <div class="alert alert-info alert-dismissible fade show">
            <strong>Active Filters:</strong>
            @if(request('date_from'))
                <span class="badge badge-primary ml-2">From: {{ request('date_from') }}</span>
            @endif
            @if(request('date_to'))
                <span class="badge badge-primary ml-2">To: {{ request('date_to') }}</span>
            @endif
            @if(request('payment_status'))
                <span class="badge badge-info ml-2">Payment: {{ ucfirst(request('payment_status')) }}</span>
            @endif
            @if(request('payment_method'))
                <span class="badge badge-secondary ml-2">Method: {{ ucfirst(request('payment_method')) }}</span>
            @endif
            @if(request('search'))
                <span class="badge badge-dark ml-2">Search: {{ request('search') }}</span>
            @endif
            <a href="{{ route('admin.pos.history') }}" class="ml-3 text-white">
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
                        Transactions
                        <span class="badge badge-primary ml-2">{{ $summary['total_sales'] }}</span>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Payment Methods</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Payment Status</th>
                                    <th>Date & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td>
                                            <small class="font-weight-bold">{{ $sale->order_number }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $sale->user->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $sale->user->phone ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $sale->orderItems->sum('quantity') }} items
                                            </span>
                                        </td>
                                        <td>
                                            @foreach($sale->payments as $payment)
                                                <span class="badge badge-secondary text-uppercase">
                                                    {{ $payment->payment_method }}
                                                    (₦{{ number_format($payment->amount, 2) }})
                                                </span><br>
                                            @endforeach
                                        </td>
                                        <td>₦{{ number_format($sale->total, 2) }}</td>
                                        <td class="text-success">
                                            ₦{{ number_format($sale->amount_paid, 2) }}
                                        </td>
                                        <td>
                                            @if($sale->balance > 0)
                                                <span class="text-danger font-weight-bold">
                                                    ₦{{ number_format($sale->balance, 2) }}
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="mdi mdi-check"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $sale->payment_status_color }}">
                                                {{ ucfirst($sale->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $sale->created_at->format('d M, Y') }}</small><br>
                                            <small class="text-muted">{{ $sale->created_at->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $sale->id) }}"
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pos.receipt', $sale->id) }}"
                                               class="btn btn-sm btn-primary" target="_blank" title="Print Receipt">
                                                <i class="mdi mdi-printer"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="mdi mdi-point-of-sale mdi-48px text-muted"></i>
                                            <p class="text-muted mt-2">No POS transactions found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script src="{{ asset('admin/js/date-filter.js') }}"></script>
@endpush