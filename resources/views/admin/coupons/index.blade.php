@extends('admin.layouts.app')
@section('title', 'Coupons')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Coupons & Discounts</h4>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Create Coupon
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Min. Purchase</th>
                                    <th>Usage</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $coupon->code }}</strong>
                                        </td>
                                        <td>{{ $coupon->description ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $coupon->type === 'percentage' ? 'info' : 'primary' }}">
                                                {{ ucfirst($coupon->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->type === 'percentage')
                                                <strong>{{ $coupon->value }}%</strong>
                                            @else
                                                <strong>₦{{ number_format($coupon->value, 2) }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $coupon->minimum_purchase 
                                                ? '₦' . number_format($coupon->minimum_purchase, 2) 
                                                : 'None' 
                                            }}
                                        </td>
                                        <td>
                                            {{ $coupon->used_count }} / 
                                            {{ $coupon->usage_limit ?? '∞' }}
                                            <div class="progress mt-1" style="height: 4px;">
                                                @php
                                                    $percentage = $coupon->usage_limit 
                                                        ? ($coupon->used_count / $coupon->usage_limit) * 100 
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($coupon->valid_from || $coupon->valid_until)
                                                <small>
                                                    {{ $coupon->valid_from?->format('d M Y') ?? '∞' }}
                                                    →
                                                    {{ $coupon->valid_until?->format('d M Y') ?? '∞' }}
                                                </small>
                                            @else
                                                <small class="text-muted">No expiry</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $coupon->status_color }}">
                                                {{ ucfirst($coupon->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="deleteCoupon({{ $coupon->id }})">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No coupons found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>

    <form id="delete-coupon-form" method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deleteCoupon(id) {
        if (confirm('Are you sure you want to delete this coupon?')) {
            const form = document.getElementById('delete-coupon-form');
            form.action = '/admin/coupons/' + id;
            form.submit();
        }
    }
    setTimeout(() => $('.alert').fadeOut(), 5000);
</script>
@endpush