@extends('admin.layouts.app')

@section('title', 'Add Stock Movement - ' . $product->name)

@section('content')

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">Add Stock Movement</h4>
                <p class="text-muted mb-0">
                    <i class="mdi mdi-package-variant"></i>
                    {{ $product->name }}
                    <span class="badge badge-secondary ml-1">SKU: {{ $product->sku }}</span>
                    <span class="badge badge-{{ $product->quantity > 0 ? 'success' : 'danger' }} ml-1">
                        Current Stock: {{ number_format($product->quantity) }} units
                    </span>
                </p>
            </div>
            <a href="{{ route('admin.stock.history', $product) }}" class="btn btn-secondary">
                <i class="mdi mdi-history"></i> View History
            </a>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="mdi mdi-alert-circle"></i>
        <strong>Please fix the following errors:</strong><br>
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="font-weight-bold mb-0">
                    <i class="mdi mdi-swap-vertical text-primary"></i> Movement Details
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stock.store', $product) }}" method="POST">
                    @csrf

                    {{-- Movement Type --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Movement Type <span class="text-danger">*</span></label>
                        <div class="row mt-2">
                            <div class="col-6 col-md-4 mb-2">
                                <div class="movement-type-card" data-type="in">
                                    <input type="radio" name="type" value="in" id="type_in"
                                           {{ old('type', 'in') === 'in' ? 'checked' : '' }} class="d-none">
                                    <label for="type_in" class="movement-label w-100 text-center p-3 border rounded cursor-pointer
                                           {{ old('type', 'in') === 'in' ? 'border-success bg-success text-white' : '' }}">
                                        <i class="mdi mdi-arrow-down-circle mdi-24px d-block"></i>
                                        <span class="font-weight-bold">Stock In</span>
                                        <small class="d-block">New stock received</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 mb-2">
                                <div class="movement-type-card" data-type="return">
                                    <input type="radio" name="type" value="return" id="type_return"
                                           {{ old('type') === 'return' ? 'checked' : '' }} class="d-none">
                                    <label for="type_return" class="movement-label w-100 text-center p-3 border rounded cursor-pointer
                                           {{ old('type') === 'return' ? 'border-info bg-info text-white' : '' }}">
                                        <i class="mdi mdi-undo mdi-24px d-block"></i>
                                        <span class="font-weight-bold">Return</span>
                                        <small class="d-block">Customer return</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 mb-2">
                                <div class="movement-type-card" data-type="adjustment">
                                    <input type="radio" name="type" value="adjustment" id="type_adjustment"
                                           {{ old('type') === 'adjustment' ? 'checked' : '' }} class="d-none">
                                    <label for="type_adjustment" class="movement-label w-100 text-center p-3 border rounded cursor-pointer
                                           {{ old('type') === 'adjustment' ? 'border-warning bg-warning text-white' : '' }}">
                                        <i class="mdi mdi-pencil mdi-24px d-block"></i>
                                        <span class="font-weight-bold">Adjustment</span>
                                        <small class="d-block">Correct stock count</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 mb-2">
                                <div class="movement-type-card" data-type="out">
                                    <input type="radio" name="type" value="out" id="type_out"
                                           {{ old('type') === 'out' ? 'checked' : '' }} class="d-none">
                                    <label for="type_out" class="movement-label w-100 text-center p-3 border rounded cursor-pointer
                                           {{ old('type') === 'out' ? 'border-danger bg-danger text-white' : '' }}">
                                        <i class="mdi mdi-arrow-up-circle mdi-24px d-block"></i>
                                        <span class="font-weight-bold">Stock Out</span>
                                        <small class="d-block">Manual removal</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 mb-2">
                                <div class="movement-type-card" data-type="damaged">
                                    <input type="radio" name="type" value="damaged" id="type_damaged"
                                           {{ old('type') === 'damaged' ? 'checked' : '' }} class="d-none">
                                    <label for="type_damaged" class="movement-label w-100 text-center p-3 border rounded cursor-pointer
                                           {{ old('type') === 'damaged' ? 'border-dark bg-dark text-white' : '' }}">
                                        <i class="mdi mdi-alert mdi-24px d-block"></i>
                                        <span class="font-weight-bold">Damaged</span>
                                        <small class="d-block">Loss / damaged goods</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div class="form-group">
                        <label for="quantity" class="font-weight-bold">
                            Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control form-control-lg @error('quantity') is-invalid @enderror"
                               id="quantity" name="quantity" value="{{ old('quantity') }}"
                               min="1" placeholder="Enter quantity" required>
                        <small class="form-text text-muted">
                            Current stock: <strong>{{ number_format($product->quantity) }} units</strong>
                            <span id="stock-preview" class="ml-2 font-weight-bold"></span>
                        </small>
                        @error('quantity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Cost Price --}}
                    <div class="form-group" id="cost-price-group">
                        <label for="cost_price" class="font-weight-bold">Cost Price per Unit</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₦</span>
                            </div>
                            <input type="number" class="form-control @error('cost_price') is-invalid @enderror"
                                   id="cost_price" name="cost_price" value="{{ old('cost_price') }}"
                                   min="0" step="0.01" placeholder="0.00">
                        </div>
                        <small class="form-text text-muted">Optional — used for profit tracking</small>
                        @error('cost_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Supplier --}}
                    <div class="form-group" id="supplier-group">
                        <label for="supplier_name" class="font-weight-bold">Supplier Name</label>
                        <input type="text" class="form-control @error('supplier_name') is-invalid @enderror"
                               id="supplier_name" name="supplier_name" value="{{ old('supplier_name') }}"
                               placeholder="e.g. Dangote Suppliers Ltd (optional)">
                        @error('supplier_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Reference Number --}}
                    <div class="form-group">
                        <label for="reference_no" class="font-weight-bold">Reference / Invoice No.</label>
                        <input type="text" class="form-control @error('reference_no') is-invalid @enderror"
                               id="reference_no" name="reference_no" value="{{ old('reference_no') }}"
                               placeholder="e.g. INV-2024-001 or waybill number">
                        @error('reference_no')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Note --}}
                    <div class="form-group">
                        <label for="note" class="font-weight-bold">Note</label>
                        <textarea class="form-control @error('note') is-invalid @enderror"
                                  id="note" name="note" rows="3"
                                  placeholder="Any additional information about this movement...">{{ old('note') }}</textarea>
                        @error('note')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex mt-4">
                        <button type="submit" class="btn btn-primary btn-lg mr-2">
                            <i class="mdi mdi-content-save"></i> Record Movement
                        </button>
                        <a href="{{ route('admin.stock.history', $product) }}" class="btn btn-light btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Sidebar --}}
    <div class="col-md-5">
        {{-- Product Info Card --}}
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="font-weight-bold mb-0">
                    <i class="mdi mdi-information text-info"></i> Product Info
                </h6>
            </div>
            <div class="card-body">
                @if($product->featured_image)
                    <img src="{{ asset('storage/' . $product->featured_image) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid rounded mb-3" style="max-height: 150px; object-fit: cover;">
                @endif
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted pl-0">Name</td>
                        <td class="font-weight-bold">{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted pl-0">SKU</td>
                        <td><code>{{ $product->sku }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted pl-0">Category</td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted pl-0">Sale Price</td>
                        <td class="font-weight-bold text-success">₦{{ number_format($product->price, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted pl-0">Current Stock</td>
                        <td>
                            <span class="badge badge-{{ $product->quantity > 5 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }} badge-pill">
                                {{ number_format($product->quantity) }} units
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Movement Type Guide --}}
        <div class="card shadow">
            <div class="card-header">
                <h6 class="font-weight-bold mb-0">
                    <i class="mdi mdi-help-circle text-warning"></i> When to use each type
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex align-items-start mb-2">
                    <span class="badge badge-success mr-2 mt-1">Stock In</span>
                    <small>New stock purchased from supplier or transferred in</small>
                </div>
                <div class="d-flex align-items-start mb-2">
                    <span class="badge badge-info mr-2 mt-1">Return</span>
                    <small>Customer returned an item back to inventory</small>
                </div>
                <div class="d-flex align-items-start mb-2">
                    <span class="badge badge-warning mr-2 mt-1">Adjustment</span>
                    <small>Correcting stock count after physical inventory</small>
                </div>
                <div class="d-flex align-items-start mb-2">
                    <span class="badge badge-danger mr-2 mt-1">Stock Out</span>
                    <small>Manual removal e.g. internal use or transfer out</small>
                </div>
                <div class="d-flex align-items-start">
                    <span class="badge badge-dark mr-2 mt-1">Damaged</span>
                    <small>Items lost, expired, stolen or damaged</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<style>
    .movement-label {
        cursor: pointer;
        transition: all 0.2s;
    }
    .movement-label:hover {
        background: #f8f9fa;
    }
    .movement-label small {
        font-size: 0.7rem;
        opacity: 0.85;
    }
</style>
<script>
    const currentStock = {{ $product->quantity }};

    // Movement type card selection
    document.querySelectorAll('.movement-type-card').forEach(card => {
        card.addEventListener('click', function () {
            const type = this.dataset.type;
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            // Reset all labels
            document.querySelectorAll('.movement-label').forEach(label => {
                label.classList.remove(
                    'border-success', 'bg-success',
                    'border-info', 'bg-info',
                    'border-warning', 'bg-warning',
                    'border-danger', 'bg-danger',
                    'border-dark', 'bg-dark',
                    'text-white'
                );
            });

            // Highlight selected
            const colorMap = {
                'in': ['border-success', 'bg-success', 'text-white'],
                'return': ['border-info', 'bg-info', 'text-white'],
                'adjustment': ['border-warning', 'bg-warning', 'text-white'],
                'out': ['border-danger', 'bg-danger', 'text-white'],
                'damaged': ['border-dark', 'bg-dark', 'text-white'],
            };
            const label = this.querySelector('.movement-label');
            colorMap[type].forEach(cls => label.classList.add(cls));

            // Show/hide supplier & cost price for outgoing types
            const isIncoming = ['in', 'return'].includes(type);
            document.getElementById('supplier-group').style.display = isIncoming ? '' : 'none';
            document.getElementById('cost-price-group').style.display = isIncoming ? '' : 'none';

            updateStockPreview();
        });
    });

    // Stock preview on quantity change
    document.getElementById('quantity').addEventListener('input', updateStockPreview);

    function updateStockPreview() {
        const qty = parseInt(document.getElementById('quantity').value) || 0;
        const type = document.querySelector('input[name="type"]:checked')?.value;
        const preview = document.getElementById('stock-preview');

        if (!qty || !type) { preview.textContent = ''; return; }

        const isIncoming = ['in', 'return'].includes(type);
        const newStock = isIncoming ? currentStock + qty : Math.max(0, currentStock - qty);

        preview.textContent = `→ New stock: ${newStock.toLocaleString()} units`;
        preview.className = 'ml-2 font-weight-bold ' + (isIncoming ? 'text-success' : 'text-danger');
    }

    // Init on load
    updateStockPreview();
</script>
@endpush