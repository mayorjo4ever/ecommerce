@extends('admin.layouts.app')

@section('title', 'Stock History - ' . $product->name)

@section('content')

{{-- Page Header --}}
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="font-weight-bold mb-0">Stock History</h4>
                <p class="text-muted mb-0">
                    <i class="mdi mdi-package-variant"></i>
                    {{ $product->name }}
                    <span class="badge badge-secondary ml-1">SKU: {{ $product->sku }}</span>
                </p>
            </div>
            <div class="d-flex mt-2 mt-md-0">
                <a href="{{ route('admin.stock.create', $product) }}" class="btn btn-primary mr-2">
                    <i class="mdi mdi-plus-circle"></i> Add Stock Movement
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-primary text-uppercase mb-1">Current Stock</p>
                        <h4 class="font-weight-bold mb-0">{{ number_format($product->quantity) }}</h4>
                        <small class="text-muted">units available</small>
                    </div>
                    <i class="mdi mdi-package-variant mdi-36px text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-left-success shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Received</p>
                        <h4 class="font-weight-bold mb-0">{{ number_format($summary['total_in']) }}</h4>
                        <small class="text-muted">units all time</small>
                    </div>
                    <i class="mdi mdi-arrow-down-circle mdi-36px text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-left-danger shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Removed</p>
                        <h4 class="font-weight-bold mb-0">{{ number_format($summary['total_out']) }}</h4>
                        <small class="text-muted">units all time</small>
                    </div>
                    <i class="mdi mdi-arrow-up-circle mdi-36px text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-left-warning shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Movements</p>
                        <h4 class="font-weight-bold mb-0">{{ number_format($summary['movements_count']) }}</h4>
                        <small class="text-muted">recorded entries</small>
                    </div>
                    <i class="mdi mdi-swap-vertical mdi-36px text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Movements Table --}}
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0">Movement Records</h6>
                <small class="text-muted">{{ $movements->total() }} total records</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Before</th>
                                <th>After</th>
                                <th>Cost/Unit</th>
                                <th>Supplier</th>
                                <th>Reference</th>
                                <th>Recorded By</th>
                                <th>Note</th>
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
                                    <td>
                                        @if($movement->cost_price)
                                            ₦{{ number_format($movement->cost_price, 2) }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $movement->supplier_name ?? '—' ?? '—' }}</td>
                                    <td>
                                        @if($movement->reference_no)
                                            <code>{{ $movement->reference_no }}</code>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $movement->createdBy?->name ?? '—' }}</td>
                                    <td>
                                        @if($movement->note)
                                            <span data-toggle="tooltip" title="{{ $movement->note }}">
                                                <i class="mdi mdi-information text-info"></i>
                                                {{ Str::limit($movement->note, 30) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="mdi mdi-swap-vertical mdi-48px text-muted d-block mb-2"></i>
                                        <p class="text-muted">No stock movements recorded yet.</p>
                                        <a href="{{ route('admin.stock.create', $product) }}" class="btn btn-primary btn-sm">
                                            Record First Movement
                                        </a>
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
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush