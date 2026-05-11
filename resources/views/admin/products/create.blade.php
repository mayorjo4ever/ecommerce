@extends('admin.layouts.app')

@section('title', 'Create Product')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/vendors/select2/select2.min.css') }}">
@endpush

@push('styles')
<style>
/* ── Section headers ─────────────────────────────────────── */
.section-icon-title {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f3fa;
    margin-bottom: 1.25rem;
}
.section-icon-title .icon-wrap {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
}
.section-icon-title h5 { margin: 0; font-size: 14px; font-weight: 700; color: #343a40; }
.section-icon-title small { font-size: 11px; color: #adb5bd; font-weight: 400; }

/* ── Input with prefix ───────────────────────────────────── */
.input-prefix-group .input-group-text {
    background: #f4f5f7;
    border-right: none;
    font-weight: 700;
    color: #495057;
    font-size: 14px;
}
.input-prefix-group .form-control { border-left: none; }
.input-prefix-group .form-control:focus { border-left: none; box-shadow: none; }
.input-prefix-group:focus-within .input-group-text {
    border-color: #727cf5;
}
.input-prefix-group:focus-within .form-control {
    border-color: #727cf5;
    box-shadow: 0 0 0 .15rem rgba(114,124,245,.2);
}

/* ── Toggle switch ───────────────────────────────────────── */
.toggle-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f3fa;
}
.toggle-wrap:last-child { border-bottom: none; }
.toggle-wrap .toggle-label { font-size: 13px; font-weight: 600; color: #343a40; }
.toggle-wrap .toggle-sub   { font-size: 11px; color: #adb5bd; margin-top: 2px; }
.toggle-switch {
    position: relative; display: inline-block;
    width: 44px; height: 24px; flex-shrink: 0;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0;
    background: #dee2e6; border-radius: 24px;
    cursor: pointer; transition: .25s;
}
.toggle-slider:before {
    content: '';
    position: absolute;
    width: 18px; height: 18px;
    left: 3px; top: 3px;
    background: #fff; border-radius: 50%;
    transition: .25s;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.toggle-switch input:checked + .toggle-slider { background: #0acf97; }
.toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }

/* ── Image drop zone ─────────────────────────────────────── */
.drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 28px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    background: #fafbff;
    position: relative;
}
.drop-zone:hover, .drop-zone.dragover {
    border-color: #727cf5;
    background: #f0f1fe;
}
.drop-zone input[type=file] {
    position: absolute; inset: 0;
    opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.drop-zone .dz-icon { font-size: 2.2rem; color: #b0b8c9; }
.drop-zone .dz-text { font-size: 13px; color: #6c757d; margin-top: 6px; }
.drop-zone .dz-hint { font-size: 11px; color: #adb5bd; margin-top: 3px; }

/* featured image preview */
.featured-preview-wrap {
    display: none;
    margin-top: 12px;
    position: relative;
    display: none;
}
.featured-preview-wrap img {
    width: 100%; max-height: 220px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}
.featured-preview-wrap .remove-featured {
    position: absolute; top: 8px; right: 8px;
    background: rgba(255,255,255,.9);
    border: none; border-radius: 50%;
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 16px; color: #fa5c7c;
    box-shadow: 0 1px 4px rgba(0,0,0,.15);
}

/* gallery preview */
.gallery-preview {
    display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px;
}
.gallery-thumb {
    width: 90px; height: 90px;
    border-radius: 8px; overflow: hidden;
    border: 1px solid #dee2e6;
    position: relative;
    flex-shrink: 0;
}
.gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
.gallery-thumb .rm-thumb {
    position: absolute; top: 3px; right: 3px;
    width: 20px; height: 20px;
    background: rgba(250,92,124,.9); color: #fff;
    border: none; border-radius: 50%; font-size: 12px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; line-height: 1;
}

/* ── Barcode field ───────────────────────────────────────── */
.barcode-input-group .input-group-text {
    background: #f4f5f7;
    border-right: none;
    font-size: 18px;
    color: #495057;
}
.barcode-input-group .form-control { border-left: none; font-family: monospace; font-size: 13px; }
.barcode-input-group .form-control:focus { border-left: none; box-shadow: none; }
.barcode-input-group:focus-within .input-group-text,
.barcode-input-group:focus-within .form-control { border-color: #727cf5; }

.scan-btn {
    transition: all .2s;
    white-space: nowrap;
}
.scan-btn.scanning {
    background: #0acf97 !important;
    border-color: #0acf97 !important;
    color: #fff !important;
    animation: pulse-scan 1s infinite;
}
@keyframes pulse-scan {
    0%,100% { box-shadow: 0 0 0 0 rgba(10,207,151,.4); }
    50%      { box-shadow: 0 0 0 6px rgba(10,207,151,0); }
}

#barcode-feedback { min-height: 18px; font-size: 12px; }

/* ── SKU auto-gen badge ──────────────────────────────────── */
.sku-auto-badge {
    display: inline-block;
    font-size: 10px; font-weight: 700;
    padding: 2px 7px; border-radius: 10px;
    background: #eef0fd; color: #727cf5;
    vertical-align: middle; margin-left: 6px;
    cursor: pointer;
}

/* ── Sidebar info card ───────────────────────────────────── */
.info-list li {
    display: flex; align-items: flex-start; gap: 8px;
    padding: 8px 0; border-bottom: 1px solid #f1f3fa;
    font-size: 12px; color: #6c757d;
}
.info-list li:last-child { border-bottom: none; }
.info-list li i { font-size: 16px; margin-top: 1px; flex-shrink: 0; }

/* ── Submit button ───────────────────────────────────────── */
.btn-submit-product {
    height: 48px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: .03em;
    border-radius: 8px;
}
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">Create New Product</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Fill in the details below to add a product to your store
                </p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
    @csrf

    <div class="row">

        {{-- ══ LEFT COLUMN ══════════════════════════════════════ --}}
        <div class="col-md-8">

            {{-- ── Basic Information ─────────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-icon-title">
                        <div class="icon-wrap bg-primary text-white" style="background:#727cf5 !important;">
                            <i class="mdi mdi-tag-outline"></i>
                        </div>
                        <div>
                            <h5>Basic Information</h5>
                            <small>Name, identifiers and category</small>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name" class="font-weight-semibold">
                            Product Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name"
                               value="{{ old('name') }}"
                               placeholder="e.g. Nike Air Max 270"
                               required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- SKU + Barcode side by side --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku" class="font-weight-semibold">
                                    SKU <span class="text-danger">*</span>
                                    <span class="sku-auto-badge" id="btn-gen-sku" title="Auto-generate">
                                        <i class="mdi mdi-refresh"></i> Auto
                                    </span>
                                </label>
                                <input type="text"
                                       class="form-control @error('sku') is-invalid @enderror"
                                       id="sku" name="sku"
                                       value="{{ old('sku') }}"
                                       placeholder="SKU-XXX-000000"
                                       style="font-family:monospace;font-size:13px;"
                                       required>
                                <small class="form-text text-muted">Your internal stock identifier</small>
                                @error('sku')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- ── BARCODE FIELD ───────────────────────────── --}}
                            <div class="form-group">
                                <label for="barcode" class="font-weight-semibold">
                                    Physical Barcode / QR
                                    <span class="text-muted" style="font-size:11px;font-weight:400;">
                                        (optional)
                                    </span>
                                </label>
                                <div class="input-group barcode-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-barcode"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('barcode') is-invalid @enderror"
                                           id="barcode" name="barcode"
                                           value="{{ old('barcode') }}"
                                           placeholder="Scan or type barcode…"
                                           autocomplete="off"
                                           spellcheck="false">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-scan" class="btn btn-outline-secondary scan-btn">
                                            <i class="mdi mdi-barcode-scan"></i>
                                            <span id="scan-label">Scan</span>
                                        </button>
                                    </div>
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="barcode-feedback" class="mt-1"></div>
                                <small class="form-text text-muted">
                                    EAN-13, UPC, GTIN or any code printed on the physical product.
                                    Click <strong>Scan</strong> then point your scanner at the product.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="form-group">
                        <label for="category_id" class="font-weight-semibold">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            <option value="">— Select a Category —</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Short Description --}}
                    <div class="form-group">
                        <label for="short_description" class="font-weight-semibold">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror"
                                  id="short_description" name="short_description"
                                  rows="2"
                                  maxlength="500"
                                  placeholder="One or two sentences shown in product listings…">{{ old('short_description') }}</textarea>
                        <div class="d-flex justify-content-between">
                            <small class="form-text text-muted">Shown in product cards and search results.</small>
                            <small id="sd-count" class="form-text text-muted">0 / 500</small>
                        </div>
                        @error('short_description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Full Description --}}
                    <div class="form-group mb-0">
                        <label for="description" class="font-weight-semibold">Full Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description"
                                  rows="5"
                                  placeholder="Detailed product description, materials, dimensions…">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Pricing & Inventory ────────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-icon-title">
                        <div class="icon-wrap text-white" style="background:#0acf97;">
                            <i class="mdi mdi-cash-multiple"></i>
                        </div>
                        <div>
                            <h5>Pricing & Inventory</h5>
                            <small>Price, sale price and stock quantity</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price" class="font-weight-semibold">
                                    Regular Price <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-prefix-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₦</span>
                                    </div>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price"
                                           value="{{ old('price') }}"
                                           placeholder="0.00" required>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sale_price" class="font-weight-semibold">
                                    Sale / Promo Price
                                </label>
                                <div class="input-group input-prefix-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₦</span>
                                    </div>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('sale_price') is-invalid @enderror"
                                           id="sale_price" name="sale_price"
                                           value="{{ old('sale_price') }}"
                                           placeholder="0.00">
                                    @error('sale_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Leave empty if no promotion.</small>
                                <div id="price-warning" class="text-danger mt-1" style="font-size:12px;display:none;">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                    Sale price must be less than regular price.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity" class="font-weight-semibold">
                                    Stock Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       id="quantity" name="quantity"
                                       value="{{ old('quantity', 0) }}"
                                       required>
                                <div id="qty-hint" class="mt-1" style="font-size:12px;"></div>
                                @error('quantity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Product Images ─────────────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-icon-title">
                        <div class="icon-wrap text-white" style="background:#ffbc00;">
                            <i class="mdi mdi-image-multiple"></i>
                        </div>
                        <div>
                            <h5>Product Images</h5>
                            <small>Featured image + optional gallery</small>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="form-group">
                        <label class="font-weight-semibold d-block mb-2">
                            Featured Image
                            <span class="text-muted" style="font-weight:400;font-size:12px;">
                                — appears as the main product photo
                            </span>
                        </label>

                        <div class="drop-zone" id="featured-drop">
                            <input type="file" id="featured_image" name="featured_image"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <i class="mdi mdi-cloud-upload-outline dz-icon"></i>
                            <p class="dz-text mb-0">Drop image here or click to browse</p>
                            <p class="dz-hint mb-0">JPEG, PNG, JPG, GIF — max 2 MB — recommended 800 × 800 px</p>
                        </div>

                        <div class="featured-preview-wrap" id="featured-preview-wrap">
                            <img id="featured-preview-img" src="" alt="Preview">
                            <button type="button" class="remove-featured" id="remove-featured"
                                    title="Remove image">×</button>
                        </div>

                        @error('featured_image')
                            <span class="text-danger d-block mt-1" style="font-size:12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>

                    {{-- Gallery --}}
                    <div class="form-group mb-0">
                        <label class="font-weight-semibold d-block mb-2">
                            Additional Gallery Images
                            <span class="text-muted" style="font-weight:400;font-size:12px;">
                                — optional, select multiple
                            </span>
                        </label>

                        <div class="drop-zone" id="gallery-drop">
                            <input type="file" id="product_images" name="product_images[]"
                                   accept="image/jpeg,image/png,image/jpg,image/gif" multiple>
                            <i class="mdi mdi-image-plus dz-icon"></i>
                            <p class="dz-text mb-0">Drop images here or click to browse</p>
                            <p class="dz-hint mb-0">Select multiple — JPEG, PNG, JPG, GIF — max 2 MB each</p>
                        </div>

                        <div class="gallery-preview" id="gallery-preview"></div>

                        @error('product_images.*')
                            <span class="text-danger d-block mt-1" style="font-size:12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

        </div>{{-- /col-md-8 --}}

        {{-- ══ RIGHT COLUMN ═════════════════════════════════════ --}}
        <div class="col-md-4">

            {{-- ── Status & Visibility ──────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-icon-title">
                        <div class="icon-wrap text-white" style="background:#fa5c7c;">
                            <i class="mdi mdi-eye-outline"></i>
                        </div>
                        <div>
                            <h5>Status & Visibility</h5>
                            <small>Control product availability</small>
                        </div>
                    </div>

                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Active</div>
                            <div class="toggle-sub">Visible in store and searchable</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Featured</div>
                            <div class="toggle-sub">Show in featured section on homepage</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ── Quick Info ────────────────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-icon-title">
                        <div class="icon-wrap text-white" style="background:#6c757d;">
                            <i class="mdi mdi-information-outline"></i>
                        </div>
                        <div>
                            <h5>What happens next?</h5>
                        </div>
                    </div>

                    <ul class="info-list list-unstyled mb-0">
                        <li>
                            <i class="mdi mdi-qrcode text-primary"></i>
                            A QR code will be auto-generated for the product
                        </li>
                        <li>
                            <i class="mdi mdi-link-variant text-primary"></i>
                            A URL slug will be created from the product name
                        </li>
                        <li>
                            <i class="mdi mdi-image-filter text-primary"></i>
                            Images are resized and optimized automatically
                        </li>
                        <li>
                            <i class="mdi mdi-barcode text-primary"></i>
                            The physical barcode links this product to POS scanning
                        </li>
                    </ul>
                </div>
            </div>

            {{-- ── Actions ───────────────────────────────────── --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-submit-product" id="btn-submit">
                        <i class="mdi mdi-content-save-outline"></i> Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-light btn-block mt-2">
                        <i class="mdi mdi-close"></i> Cancel
                    </a>
                </div>
            </div>

        </div>{{-- /col-md-4 --}}
    </div>
</form>

@endsection

@push('custom-scripts')
<script>
(function () {
'use strict';

/* ── Short description counter ─────────────────────────── */
const sdArea  = document.getElementById('short_description');
const sdCount = document.getElementById('sd-count');
sdArea.addEventListener('input', () => {
    sdCount.textContent = sdArea.value.length + ' / 500';
});

/* ── SKU auto-generate ─────────────────────────────────── */
function generateSKU() {
    const name = document.getElementById('name').value.trim();
    const prefix = name.length >= 3 ? name.substring(0, 3).toUpperCase() : 'PRD';
    document.getElementById('sku').value = 'SKU-' + prefix + '-' + Date.now().toString().slice(-6);
}
document.getElementById('name').addEventListener('input', function () {
    if (document.getElementById('sku').dataset.manuallyEdited !== '1') generateSKU();
});
document.getElementById('sku').addEventListener('input', function () {
    this.dataset.manuallyEdited = '1';
});
document.getElementById('btn-gen-sku').addEventListener('click', generateSKU);

/* ── Price validation ──────────────────────────────────── */
function checkPrice() {
    const reg  = parseFloat(document.getElementById('price').value) || 0;
    const sale = parseFloat(document.getElementById('sale_price').value) || 0;
    const warn = document.getElementById('price-warning');
    const sp   = document.getElementById('sale_price');
    if (sale > 0 && sale >= reg) {
        warn.style.display = 'block';
        sp.classList.add('is-invalid');
    } else {
        warn.style.display = 'none';
        sp.classList.remove('is-invalid');
    }
}
document.getElementById('price').addEventListener('input', checkPrice);
document.getElementById('sale_price').addEventListener('input', checkPrice);

/* ── Quantity hint ─────────────────────────────────────── */
document.getElementById('quantity').addEventListener('input', function () {
    const qty  = parseInt(this.value) || 0;
    const hint = document.getElementById('qty-hint');
    if (qty === 0)      hint.innerHTML = '<span class="text-danger"><i class="mdi mdi-alert-circle-outline"></i> Out of stock</span>';
    else if (qty <= 10) hint.innerHTML = '<span class="text-warning"><i class="mdi mdi-alert-outline"></i> Low stock</span>';
    else                hint.innerHTML = '<span class="text-success"><i class="mdi mdi-check-circle-outline"></i> In stock</span>';
});

/* ── Featured image drop zone ──────────────────────────── */
const featuredInput   = document.getElementById('featured_image');
const featuredWrap    = document.getElementById('featured-preview-wrap');
const featuredPreview = document.getElementById('featured-preview-img');
const removeFeatured  = document.getElementById('remove-featured');
const featuredDrop    = document.getElementById('featured-drop');

featuredInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        featuredPreview.src = e.target.result;
        featuredWrap.style.display = 'block';
        featuredDrop.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
removeFeatured.addEventListener('click', () => {
    featuredInput.value = '';
    featuredWrap.style.display = 'none';
    featuredDrop.style.display = 'block';
});

// Drag and drop
['dragover','dragleave','drop'].forEach(ev => {
    featuredDrop.addEventListener(ev, e => {
        e.preventDefault();
        featuredDrop.classList.toggle('dragover', ev === 'dragover');
        if (ev === 'drop') {
            featuredInput.files = e.dataTransfer.files;
            featuredInput.dispatchEvent(new Event('change'));
        }
    });
});

/* ── Gallery drop zone ─────────────────────────────────── */
const galleryInput   = document.getElementById('product_images');
const galleryPreview = document.getElementById('gallery-preview');
const galleryDrop    = document.getElementById('gallery-drop');
let galleryFiles     = [];

function renderGallery() {
    galleryPreview.innerHTML = '';
    galleryFiles.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const thumb = document.createElement('div');
            thumb.className = 'gallery-thumb';
            thumb.innerHTML = `<img src="${e.target.result}" alt="">
                <button type="button" class="rm-thumb" data-index="${i}">×</button>`;
            galleryPreview.appendChild(thumb);
        };
        reader.readAsDataURL(file);
    });
}
galleryInput.addEventListener('change', function () {
    galleryFiles = [...galleryFiles, ...Array.from(this.files)];
    renderGallery();
});
galleryPreview.addEventListener('click', e => {
    if (e.target.classList.contains('rm-thumb')) {
        galleryFiles.splice(parseInt(e.target.dataset.index), 1);
        renderGallery();
    }
});

/* ── Barcode scan mode ─────────────────────────────────── */
const barcodeInput = document.getElementById('barcode');
const scanBtn      = document.getElementById('btn-scan');
const scanLabel    = document.getElementById('scan-label');
const bFeedback    = document.getElementById('barcode-feedback');
let scanning       = false;
let scanTimeout    = null;
let dupTimer       = null;

scanBtn.addEventListener('click', () => {
    if (scanning) return;
    scanning = true;
    barcodeInput.value = '';
    barcodeInput.focus();
    scanBtn.classList.add('scanning');
    scanLabel.textContent = 'Scanning…';
    bFeedback.innerHTML = '<span class="text-muted"><i class="mdi mdi-barcode-scan"></i> Point your scanner at the product now…</span>';
    scanTimeout = setTimeout(resetScan, 15000);
});

barcodeInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && scanning) {
        e.preventDefault();
        clearTimeout(scanTimeout);
        resetScan();
        checkBarcodeDuplicate();
    }
});

barcodeInput.addEventListener('input', () => {
    clearTimeout(dupTimer);
    dupTimer = setTimeout(checkBarcodeDuplicate, 600);
});

function resetScan() {
    scanning = false;
    scanBtn.classList.remove('scanning');
    scanLabel.textContent = 'Scan';
}

function checkBarcodeDuplicate() {
    const val = barcodeInput.value.trim();
    if (val.length < 3) { bFeedback.innerHTML = ''; return; }

    const url = '{{ route('admin.products.check-barcode') }}?barcode=' + encodeURIComponent(val);
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (data.taken) {
                bFeedback.innerHTML = `<span class="text-danger"><i class="mdi mdi-alert-circle-outline"></i>
                    Already assigned to <strong>${data.product}</strong></span>`;
                barcodeInput.classList.add('is-invalid');
            } else {
                bFeedback.innerHTML = `<span class="text-success"><i class="mdi mdi-check-circle-outline"></i>
                    Barcode is available</span>`;
                barcodeInput.classList.remove('is-invalid');
            }
        })
        .catch(() => bFeedback.innerHTML = '');
}

/* ── Submit guard ──────────────────────────────────────── */
document.getElementById('product-form').addEventListener('submit', function () {
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Saving…';
});

})();
</script>
@endpush
