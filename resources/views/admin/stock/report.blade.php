@extends('admin.layouts.app')

@section('title', 'Stock Movement Report')

@section('content')

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">Stock Movement Report</h4>
                <p class="text-muted">Full audit trail of all stock changes across all products</p>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.stock.report') }}" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold">Product</label>
                <select name="product_id" class="form-control form-control-sm">
                    <option value="">All Products</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">Type</label>
                <select name="type" class="form-control form-control-sm">
                    <option value="">All Types</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stock In</option>
                    <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>Return</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stock Out</option>
                    <option value="damaged" {{ request('type') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">From</label>
                <input type="date" name="from" class="form-control form-control-sm"
                       value="{{ request('from') }}">
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">To</label>
                <input type="date" name="to" class="form-control form-control-sm"
                       value="{{ request('to') }}">
            </div>
            <div class="col-md-3 mb-2 d-flex">
                <button type="submit" class="btn btn-primary btn-sm mr-2">
                    <i class="mdi mdi-filter"></i> Filter
                </button>
                <a href="{{ route('admin.stock.report') }}" class="btn btn-light btn-sm">
                    <i class="mdi mdi-refresh"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="font-weight-bold mb-0">All Movements</h6>
        <small class="text-muted">{{ $movements->total() }} records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Cost/Unit</th>
                        <th>Supplier</th>
                        <th>Reference</th>
                        <th>Recorded By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td>{{ $loop->iteration + ($movements->currentPage() - 1) * $movements->perPage() }}</td>
                            <td>
                                <span class="d-block">{{ $movement->created_at->format('d M Y') }}</span>
                                <small class="text-muted">{{ $movement->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.stock.history', $movement->product) }}"
                                   class="font-weight-bold text-primary">
                                    {{ $movement->product?->name }}
                                </a>
                                <small class="text-muted d-block">{{ $movement->product?->sku }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $movement->type_badge }}">
                                    {{ $movement->type_label }}
                                </span>
                            </td>
                            <td>
                                @if(in_array($movement->type, ['in', 'return']))
                                    <span class="text-success font-weight-bold">+{{ number_format($movement->quantity) }}</span>
                                @else
                                    <span class="text-danger font-weight-bold">-{{ number_format($movement->quantity) }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-light">{{ number_format($movement->quantity_before) }}</span></td>
                            <td><span class="badge badge-light">{{ number_format($movement->quantity_after) }}</span></td>
                            <td>{{ $movement->cost_price ? '₦' . number_format($movement->cost_price, 2) : '—' }}</td>
                            <td>{{ $movement->supplier_name ?? '—' ?? '—' }}</td>
                            <td>{{ $movement->reference_no ? $movement->reference_no : '—' }}</td>
                            <td>{{ $movement->createdBy?->name ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.stock.history', $movement->product) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-history"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-5">
                                <i class="mdi mdi-swap-vertical mdi-48px text-muted d-block mb-2"></i>
                                <p class="text-muted">No stock movements found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($movements->hasPages())
        <div class="card-footer">
            {{ $movements->links() }}
        </div>
    @endif
</div>

@endsection