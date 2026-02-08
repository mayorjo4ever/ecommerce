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
                    <i class="mdi mdi-point-of-sale"></i> Back to POS
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Today's Sales</h6>
                    <h3 class="mb-0">{{ $sales->where('created_at', '>=', today())->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Today's Revenue</h6>
                    <h3 class="mb-0">₦{{ number_format($sales->where('created_at', '>=', today())->sum('total'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Sales</h6>
                    <h3 class="mb-0">{{ $sales->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="mb-0">₦{{ number_format($sales->sum('total'), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">All POS Transactions</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Payment Method</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->order_number }}</td>
                                        <td>{{ $sale->user->name }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $sale->orderItems->sum('quantity') }} items
                                            </span>
                                        </td>
                                        <td class="text-uppercase">{{ $sale->payment->payment_method ?? 'N/A' }}</td>
                                        <td>₦{{ number_format($sale->total, 2) }}</td>
                                        <td>{{ $sale->created_at->format('d M, Y H:i') }}</td>
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
                                        <td colspan="7" class="text-center">No POS sales found</td>
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