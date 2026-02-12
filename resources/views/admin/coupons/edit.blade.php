@extends('admin.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Edit Coupon: {{ $coupon->code }}</h4>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
        </div>
    @endif

    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Coupon Details</h4>

                        <div class="form-group">
                            <label>Coupon Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                       name="code" id="coupon-code"
                                       value="{{ old('code', $coupon->code) }}"
                                       style="text-transform:uppercase;" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-primary"
                                            onclick="generateCode()">
                                        <i class="mdi mdi-refresh"></i> Generate New
                                    </button>
                                </div>
                            </div>
                            @error('code')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" name="description"
                                   value="{{ old('description', $coupon->description) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discount Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="type" id="discount-type" onchange="toggleValueLabel()">
                                        <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (₦)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discount Value <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="value-prefix">
                                                {{ $coupon->type === 'percentage' ? '%' : '₦' }}
                                            </span>
                                        </div>
                                        <input type="number" class="form-control"
                                               name="value" value="{{ old('value', $coupon->value) }}"
                                               step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minimum Purchase Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₦</span>
                                        </div>
                                        <input type="number" class="form-control"
                                               name="minimum_purchase"
                                               value="{{ old('minimum_purchase', $coupon->minimum_purchase) }}"
                                               step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Usage Limit</label>
                                    <input type="number" class="form-control"
                                           name="usage_limit"
                                           value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                           min="1" placeholder="Unlimited">
                                    <small class="text-muted">Used: {{ $coupon->used_count }} times</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Valid From</label>
                                    <input type="date" class="form-control"
                                           name="valid_from"
                                           value="{{ old('valid_from', $coupon->valid_from?->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Valid Until</label>
                                    <input type="date" class="form-control"
                                           name="valid_until"
                                           value="{{ old('valid_until', $coupon->valid_until?->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Status</h4>
                        <div class="form-check mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input"
                                       name="is_active" value="1"
                                       {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                Active
                                <i class="input-helper"></i>
                            </label>
                        </div>
                        <div>
                            <strong>Current Status:</strong><br>
                            <span class="badge badge-{{ $coupon->status_color }}">
                                {{ ucfirst($coupon->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Statistics</h4>
                        <div class="mb-2">
                            <strong>Times Used:</strong> {{ $coupon->used_count }}
                        </div>
                        <div class="mb-2">
                            <strong>Remaining Uses:</strong> {{ $coupon->remaining_uses }}
                        </div>
                        <div class="mb-2">
                            <strong>Created:</strong> {{ $coupon->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="mdi mdi-content-save"></i> Update Coupon
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-block">
                            Cancel
                        </a>
                        <button type="button" class="btn btn-danger btn-block"
                                onclick="deleteCoupon({{ $coupon->id }})">
                            <i class="mdi mdi-delete"></i> Delete Coupon
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="delete-coupon-form" action="{{ route('admin.coupons.destroy', $coupon->id) }}"
          method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function generateCode() {
        $.get('{{ route("admin.coupons.generate") }}', function(response) {
            $('#coupon-code').val(response.code);
        });
    }

    function toggleValueLabel() {
        const type = $('#discount-type').val();
        $('#value-prefix').text(type === 'percentage' ? '%' : '₦');
    }

    function deleteCoupon(id) {
        if (confirm('Are you sure you want to delete this coupon?')) {
            document.getElementById('delete-coupon-form').submit();
        }
    }

    toggleValueLabel();
</script>
@endpush