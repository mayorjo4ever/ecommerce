@extends('admin.layouts.app')

@section('title', 'Edit: ' . $product->name)

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/vendors/select2/select2.min.css') }}">
@endpush

@push('styles')
<style>
/* ── Reuse same styles as create ─────────────────────────── */
.section-icon-title {
    display: flex; align-items: center; gap: 10px;
    padding-bottom: 10px; border-bottom: 2px solid #f1f3fa; margin-bottom: 1.25rem;
}
.section-icon-title .icon-wrap {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
}
.section-icon-title h5 { margin: 0; font-size: 14px; font-weight: 700; color: #343a40; }
.section-icon-title small { font-size: 11px; color: #adb5bd; }

.input-prefix-group .input-group-text {
    background: #f4f5f7; border-right: none;
    font-weight: 700; color: #495057; font-size: 14px;
}
.input-prefix-group .form-control { border-left: none; }
.input-prefix-group .form-control:focus { border-left: none; box-shadow: none; }
.input-prefix-group:focus-within .input-group-text,
.input-prefix-group:focus-within .form-control { border-color: #727cf5; }

.toggle-wrap {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid #f1f3fa;
}
.toggle-wrap:last-child { border-bottom: none; }
.toggle-label { font-size: 13px; font-weight: 600; color: #343a40; }
.toggle-sub   { font-size: 11px; color: #adb5bd; margin-top: 2px; }
.toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0; background: #dee2e6;
    border-radius: 24px; cursor: pointer; transition: .25s;
}
.toggle-slider:before {
    content: ''; position: absolute;
    width: 18px; height: 18px; left: 3px; top: 3px;
    background: #fff; border-radius: 50%; transition: .25s;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.toggle-switch input:checked + .toggle-slider { background: #0acf97; }
.toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }

/* Image section */
.current-featured-wrap { position: relative; display: inline-block; }
.current-featured-wrap img {
    width: 100%; max-height: 200px; object-fit: cover;
    border-radius: 8px; border: 1px solid #dee2e6;
}
.drop-zone {
    border: 2px dashed #dee2e6; border-radius: 10px;
    padding: 20px; text-align: center; cursor: pointer;
    transition: border-color .2s, background .2s;
    background: #fafbff; position: relative;
}
.drop-zone:hover, .drop-zone.dragover { border-color: #727cf5; background: #f0f1fe; }
.drop-zone input[type=file] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
    width: 100%; height: 100%;
}
.drop-zone .dz-icon { font-size: 1.8rem; color: #b0b8c9; }
.drop-zone .dz-text { font-size: 12px; color: #6c757d; margin-top: 4px; }
.drop-zone .dz-hint { font-size: 11px; color: #adb5bd; }

.new-preview-wrap { display: none; margin-top: 10px; }
.new-preview-wrap img {
    width: 100%; max-height: 160px; object-fit: cover;
    border-radius: 8px; border: 2px solid #727cf5;
}
.new-preview-label {
    font-size: 10px; font-weight: 700; color: #727cf5;
    text-transform: uppercase; letter-spacing: .04em;
    margin-bottom: 4px;
}

/* Gallery grid */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 10px; margin-top: 10px;
}
.gallery-item {
    position: relative; border-radius: 8px; overflow: hidden;
    border: 1px solid #dee2e6; aspect-ratio: 1/1;
}
.gallery-item img { width: 100%; height: 100%; object-fit: cover; }
.gallery-item .del-img {
    position: absolute; top: 4px; right: 4px;
    width: 22px; height: 22px;
    background: rgba(250,92,124,.9); color: #fff;
    border: none; border-radius: 50%; font-size: 13px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: transform .15s;
}
.gallery-item .del-img:hover { transform: scale(1.15); }
.gallery-item.removing {
    opacity: .4; pointer-events: none;
    transition: opacity .3s;
}

/* new gallery thumbs */
.new-thumb {
    position: relative; border-radius: 8px; overflow: hidden;
    border: 2px solid #727cf5; aspect-ratio: 1/1;
}
.new-thumb img { width: 100%; height: 100%; object-fit: cover; }
.new-thumb .rm-new {
    position: absolute; top: 3px; right: 3px;
    width: 20px; height: 20px;
    background: rgba(250,92,124,.9); color: #fff;
    border: none; border-radius: 50%; font-size: 12px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
}
.new-label-tag {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: rgba(114,124,245,.8); color: #fff;
    font-size: 9px; font-weight: 700; text-align: center;
    padding: 2px; letter-spacing: .04em;
}

/* Barcode */
.barcode-input-group .input-group-text {
    background: #f4f5f7; border-right: none;
    font-size: 18px; color: #495057;
}
.barcode-input-group .form-control {
    border-left: none; font-family: monospace; font-size: 13px;
}
.barcode-input-group .form-control:focus { border-left: none; box-shadow: none; }
.barcode-input-group:focus-within .input-group-text,
.barcode-input-group:focus-within .form-control { border-color: #727cf5; }

.scan-btn { transition: all .2s; white-space: nowrap; }
.scan-btn.scanning {
    background: #0acf97 !important; border-color: #0acf97 !important;
    color: #fff !important; animation: pulse-scan 1s infinite;
}
@keyframes pulse-scan {
    0%,100% { box-shadow: 0 0 0 0 rgba(10,207,151,.4); }
    50%      { box-shadow: 0 0 0 6px rgba(10,207,151,0); }
}
#barcode-feedback { min-height: 18px; font-size: 12px; }

/* Product meta sidebar */
.meta-item {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 8px 0; border-bottom: 1px solid #f1f3fa; font-size: 12px;
}
.meta-item:last-child { border-bottom: none; }
.meta-key { color: #adb5bd; font-weight: 600; text-transform: uppercase; font-size: 10px; letter-spacing:.04em; }
.meta-val { color: #343a40; font-weight: 500; text-align: right; }

/* Danger zone */
.danger-zone {
    border: 1px solid #fde8ed !important;
    border-left: 4px solid #fa5c7c !important;
    background: #fffafa;
}

.btn-submit-product { height: 48px; font-size: 14px; font-weight: 700; letter-spacing: .03em; border-radius: 8px; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">Edit Product</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    <code style="font-size:11px;">{{ $product->sku }}</code>
                    &nbsp;·&nbsp;
                    <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}"
                          style="font-size:10px;">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-eye"></i> View
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.update', $product->id) }}"
      method="POST" enctype="multipart/form-data" id="product-form">
    @csrf @method('PUT')

    <div class="row">

        {{-- ══ LEFT COLUMN ══════════════════════════════════════ --}}
        <div class="col-md-8">

            {{-- ── Basic Information ─────────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-icon-title">
                        <div class="icon-wrap text-white" style="background:#727cf5;">
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
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- SKU + Barcode --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku" class="font-weight-semibold">
                                    SKU <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('sku') is-invalid @enderror"
                                       id="sku" name="sku"
                                       value="{{ old('sku', $product->sku) }}"
                                       style="font-family:monospace;font-size:13px;"
                                       required>
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
                                    <span class="text-muted" style="font-size:11px;font-weight:400;">(optional)</span>
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
                                           value="{{ old('barcode', $product->barcode) }}"
                                           placeholder="Scan or type barcode…"
                                           autocomplete="off" spellcheck="false">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-scan" class="btn btn-outline-secondary scan-btn">
                                            <i class="mdi mdi-barcode-scan"></i>
                                            <span id="scan-label">Scan</span>
                                        </button>
                                        @if($product->barcode)
                                        <button type="button" class="btn btn-outline-danger"
                                                onclick="document.getElementById('barcode').value=''"
                                                title="Clear barcode">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                        @endif
                                    </div>
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="barcode-feedback" class="mt-1">
                                    @if($product->barcode)
                                        <span class="text-success" style="font-size:12px;">
                                            <i class="mdi mdi-check-circle-outline"></i>
                                            Barcode registered
                                        </span>
                                    @endif
                                </div>
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
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                  rows="2" maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>
                        <div class="d-flex justify-content-between">
                            <small class="form-text text-muted">Shown in product cards.</small>
                            <small id="sd-count" class="form-text text-muted">
                                {{ strlen(old('short_description', $product->short_description ?? '')) }} / 500
                            </small>
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
                                  rows="5">{{ old('description', $product->description) }}</textarea>
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
                                           value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sale_price" class="font-weight-semibold">Sale / Promo Price</label>
                                <div class="input-group input-prefix-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₦</span>
                                    </div>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('sale_price') is-invalid @enderror"
                                           id="sale_price" name="sale_price"
                                           value="{{ old('sale_price', $product->sale_price) }}">
                                    @error('sale_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div id="price-warning" class="text-danger mt-1" style="font-size:12px;display:none;">
                                    <i class="mdi mdi-alert-circle-outline"></i> Must be less than regular price.
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
                                       value="{{ old('quantity', $product->quantity) }}" required>
                                <div id="qty-hint" class="mt-1" style="font-size:12px;">
                                    @php $qty = old('quantity', $product->quantity); @endphp
                                    @if($qty == 0)
                                        <span class="text-danger"><i class="mdi mdi-alert-circle-outline"></i> Out of stock</span>
                                    @elseif($qty <= 10)
                                        <span class="text-warning"><i class="mdi mdi-alert-outline"></i> Low stock</span>
                                    @else
                                        <span class="text-success"><i class="mdi mdi-check-circle-outline"></i> In stock</span>
                                    @endif
                                </div>
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
                            <small>Featured image + gallery</small>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="form-group">
                        <label class="font-weight-semibold d-block mb-2">Featured Image</label>

                        @if($product->featured_image)
                            <div class="current-featured-wrap mb-2">
                                <img src="{{ asset('storage/' . $product->featured_image) }}"
                                     alt="{{ $product->name }}" id="current-featured-img">
                                <div style="margin-top:6px;">
                                    <small class="text-muted">
                                        <i class="mdi mdi-image-check text-success"></i>
                                        Current featured image
                                    </small>
                                </div>
                            </div>
                        @endif

                        <div class="drop-zone" id="featured-drop">
                            <input type="file" id="featured_image" name="featured_image"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <i class="mdi mdi-image-edit-outline dz-icon"></i>
                            <p class="dz-text mb-0">
                                {{ $product->featured_image ? 'Drop new image to replace' : 'Drop image or click to browse' }}
                            </p>
                            <p class="dz-hint mb-0">JPEG, PNG, JPG, GIF — max 2 MB</p>
                        </div>

                        <div class="new-preview-wrap" id="new-featured-wrap">
                            <div class="new-preview-label">
                                <i class="mdi mdi-arrow-up-circle"></i> New image — will replace current
                            </div>
                            <img id="new-featured-img" src="" alt="New featured">
                        </div>

                        @error('featured_image')
                            <span class="text-danger d-block mt-1" style="font-size:12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>

                    {{-- Gallery --}}
                    <div class="form-group mb-0">
                        <label class="font-weight-semibold d-block mb-2">
                            Gallery Images
                            @if($product->images->count())
                                <span class="badge badge-secondary ml-1">{{ $product->images->count() }}</span>
                            @endif
                        </label>

                        {{-- Existing gallery --}}
                        @if($product->images->count())
                            <div class="gallery-grid" id="existing-gallery">
                                @foreach($product->images as $img)
                                    <div class="gallery-item" id="img-{{ $img->id }}">
                                        <img src="{{ asset('storage/' . $img->image_path) }}"
                                             alt="Gallery image {{ $loop->iteration }}">
                                        <button type="button"
                                                class="del-img"
                                                data-id="{{ $img->id }}"
                                                title="Remove image">×</button>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size:11px;">
                                <i class="mdi mdi-information-outline"></i>
                                Click × to remove an image immediately (no page reload required).
                            </small>
                        @endif

                        {{-- Add new gallery images --}}
                        <div class="drop-zone mt-3" id="gallery-drop">
                            <input type="file" id="product_images" name="product_images[]"
                                   accept="image/jpeg,image/png,image/jpg,image/gif" multiple>
                            <i class="mdi mdi-image-plus dz-icon"></i>
                            <p class="dz-text mb-0">Add more images</p>
                            <p class="dz-hint mb-0">Select multiple — JPEG, PNG, JPG, GIF — max 2 MB each</p>
                        </div>

                        <div class="gallery-grid mt-2" id="new-gallery-preview"></div>

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
                        </div>
                    </div>

                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Active</div>
                            <div class="toggle-sub">Visible in store</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Featured</div>
                            <div class="toggle-sub">Show in featured section</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ── Product Meta ──────────────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-icon-title">
                        <div class="icon-wrap text-white" style="background:#6c757d;">
                            <i class="mdi mdi-clipboard-list-outline"></i>
                        </div>
                        <div><h5>Product Info</h5></div>
                    </div>

                    <div class="meta-item">
                        <span class="meta-key">Created</span>
                        <span class="meta-val">{{ $product->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-key">Updated</span>
                        <span class="meta-val">{{ $product->updated_at->format('d M Y, g:ia') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-key">Total Sold</span>
                        <span class="meta-val">{{ $product->orderItems->sum('quantity') ?? 0 }} units</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-key">Slug</span>
                        <span class="meta-val" style="font-size:11px;word-break:break-all;">
                            {{ $product->slug }}
                        </span>
                    </div>
                    @if($product->barcode)
                    <div class="meta-item">
                        <span class="meta-key">Barcode</span>
                        <span class="meta-val" style="font-family:monospace;font-size:11px;">
                            {{ $product->barcode }}
                        </span>
                    </div>
                    @endif

                    @if($product->qr_code)
                        <div class="text-center mt-3 pt-2" style="border-top:1px solid #f1f3fa;">
                            <small class="d-block text-muted mb-1" style="font-size:11px;text-transform:uppercase;font-weight:700;letter-spacing:.04em;">
                                System QR Code
                            </small>
                            <img src="{{ asset('storage/' . $product->qr_code) }}"
                                 alt="QR Code"
                                 style="width:90px;height:90px;border:1px solid #dee2e6;border-radius:6px;padding:4px;">
                            <br>
                            <a href="{{ asset('storage/' . $product->qr_code) }}"
                               download="{{ $product->sku }}_qr.png"
                               class="btn btn-outline-secondary btn-sm mt-2" style="font-size:11px;">
                                <i class="mdi mdi-download"></i> Download
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Actions ───────────────────────────────────── --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-submit-product" id="btn-submit">
                        <i class="mdi mdi-content-save-outline"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.products.show', $product) }}"
                       class="btn btn-light btn-block mt-2">
                        <i class="mdi mdi-eye"></i> View Product
                    </a>
                    <a href="{{ route('admin.stock.create', $product) }}"
                       class="btn btn-outline-success btn-block mt-2">
                        <i class="mdi mdi-package-variant"></i> Adjust Stock
                    </a>
                </div>
            </div>

            {{-- ── Danger Zone ───────────────────────────────── --}}
            <div class="card border-0 shadow-sm danger-zone">
                <div class="card-body">
                    <p class="font-weight-bold mb-1" style="font-size:13px;color:#fa5c7c;">
                        <i class="mdi mdi-alert-outline"></i> Danger Zone
                    </p>
                    <p class="text-muted mb-3" style="font-size:12px;">
                        Deleting this product removes all images, QR codes and sales history links permanently.
                    </p>
                    <button type="button"
                            class="btn btn-outline-danger btn-block btn-sm"
                            onclick="confirmDelete()">
                        <i class="mdi mdi-trash-can-outline"></i> Delete Product
                    </button>
                </div>
            </div>

        </div>{{-- /col-md-4 --}}
    </div>
</form>

{{-- Hidden delete form --}}
<form id="delete-product-form"
      action="{{ route('admin.products.destroy', $product->id) }}"
      method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

@endsection

@push('custom-scripts')
<script>
(function () {
'use strict';

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
          || '{{ csrf_token() }}';

/* ── Short description counter ─────────────────────────── */
const sdArea  = document.getElementById('short_description');
const sdCount = document.getElementById('sd-count');
sdArea.addEventListener('input', () => {
    sdCount.textContent = sdArea.value.length + ' / 500';
});

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

/* ── Featured image preview ────────────────────────────── */
const featuredInput  = document.getElementById('featured_image');
const newFeatWrap    = document.getElementById('new-featured-wrap');
const newFeatImg     = document.getElementById('new-featured-img');
const featuredDrop   = document.getElementById('featured-drop');

featuredInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        newFeatImg.src = e.target.result;
        newFeatWrap.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
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

/* ── New gallery preview ───────────────────────────────── */
const galleryInput   = document.getElementById('product_images');
const newGallPreview = document.getElementById('new-gallery-preview');
let newFiles = [];

galleryInput.addEventListener('change', function () {
    newFiles = [...newFiles, ...Array.from(this.files)];
    renderNewGallery();
});
newGallPreview.addEventListener('click', e => {
    if (e.target.classList.contains('rm-new')) {
        newFiles.splice(parseInt(e.target.dataset.index), 1);
        renderNewGallery();
    }
});
function renderNewGallery() {
    newGallPreview.innerHTML = '';
    newFiles.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const d = document.createElement('div');
            d.className = 'new-thumb';
            d.innerHTML = `<img src="${e.target.result}" alt="">
                <div class="new-label-tag">NEW</div>
                <button type="button" class="rm-new" data-index="${i}">×</button>`;
            newGallPreview.appendChild(d);
        };
        reader.readAsDataURL(file);
    });
}

/* ── AJAX delete existing gallery image ────────────────── */
document.getElementById('existing-gallery')?.addEventListener('click', function (e) {
    const btn = e.target.closest('.del-img');
    if (!btn) return;

    const imageId = btn.dataset.id;
    const item    = document.getElementById('img-' + imageId);

    if (!confirm('Remove this image? This cannot be undone.')) return;

    item.classList.add('removing');

    fetch('{{ url('admin/products/images') }}/' + imageId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            item.remove();
        } else {
            item.classList.remove('removing');
            alert(data.message || 'Could not delete image.');
        }
    })
    .catch(() => {
        item.classList.remove('removing');
        alert('Network error — please try again.');
    });
});

/* ── Barcode scan mode ─────────────────────────────────── */
const barcodeInput = document.getElementById('barcode');
const scanBtn      = document.getElementById('btn-scan');
const scanLabel    = document.getElementById('scan-label');
const bFeedback    = document.getElementById('barcode-feedback');
let scanning   = false;
let scanTimeout = null;
let dupTimer    = null;
const currentProductId = '{{ $product->id }}';

scanBtn.addEventListener('click', () => {
    if (scanning) return;
    scanning = true;
    barcodeInput.value = '';
    barcodeInput.focus();
    scanBtn.classList.add('scanning');
    scanLabel.textContent = 'Scanning…';
    bFeedback.innerHTML = '<span class="text-muted"><i class="mdi mdi-barcode-scan"></i> Point scanner at product now…</span>';
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
    const url = '{{ route('admin.products.check-barcode') }}?barcode='
              + encodeURIComponent(val) + '&exclude=' + currentProductId;
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

/* ── Delete product ────────────────────────────────────── */
window.confirmDelete = function () {
    if (confirm('Permanently delete "{{ addslashes($product->name) }}"?\n\nAll images and QR codes will also be removed. This cannot be undone.')) {
        document.getElementById('delete-product-form').submit();
    }
};

})();
</script>
@endpush
