@extends('admin.layouts.app')

@section('title', 'Product: ' . $product->name)

@push('styles')
<style>
    .product-hero-img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }
    .product-hero-placeholder {
        width: 100%;
        aspect-ratio: 1/1;
        background: #f4f5f7;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed #dee2e6;
    }
    .thumb-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: border-color .2s;
    }
    .thumb-img:hover,
    .thumb-img.active { border-color: #727cf5; }

    .detail-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #adb5bd;
        margin-bottom: 2px;
    }
    .detail-value {
        font-size: 14px;
        font-weight: 500;
        color: #343a40;
    }
    .detail-row { margin-bottom: 1rem; }

    .section-heading {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #727cf5;
        border-bottom: 2px solid #eef0fd;
        padding-bottom: 6px;
        margin-bottom: 1rem;
    }

    .badge-stock-ok  { background: #0acf97; color:#fff; }
    .badge-stock-low { background: #ffbc00; color:#fff; }
    .badge-stock-out { background: #fa5c7c; color:#fff; }

    .qr-img {
        width: 120px;
        height: 120px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 6px;
        background: #fff;
    }

    .gallery-strip { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }

    /* review stars */
    .star-filled { color: #ffbc00; }
    .star-empty  { color: #dee2e6; }

    .review-item {
        border-bottom: 1px solid #f1f3fa;
        padding: 12px 0;
    }
    .review-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="row">
    <div class="col-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.products.index') }}">Products</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ Str::limit($product->name, 40) }}
                    </li>
                </ol>
            </nav>
            <div class="d-flex gap-2" style="gap:.5rem;">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="btn btn-primary btn-sm">
                    <i class="mdi mdi-pencil"></i> Edit
                </a>
                <a href="{{ route('admin.stock.history', $product) }}"
                   class="btn btn-outline-success btn-sm">
                    <i class="mdi mdi-package-variant"></i> Stock History
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">

    {{-- ── Left column: images ──────────────────────────────── --}}
    <div class="col-md-4 col-lg-3 grid-margin">
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                {{-- Featured image --}}
                @if($product->featured_image)
                    <img id="main-image"
                         src="{{ asset('storage/' . $product->featured_image) }}"
                         alt="{{ $product->name }}"
                         class="product-hero-img mb-3">
                @else
                    <div class="product-hero-placeholder mb-3">
                        <i class="mdi mdi-image-off text-muted" style="font-size:3rem;"></i>
                    </div>
                @endif

                {{-- Gallery thumbnails --}}
                @if($product->images->isNotEmpty())
                    <p class="section-heading">Gallery</p>
                    <div class="gallery-strip">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}"
                                 class="thumb-img active"
                                 onclick="swapImage(this, '{{ asset('storage/' . $product->featured_image) }}')"
                                 alt="Featured">
                        @endif
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                 class="thumb-img"
                                 onclick="swapImage(this, '{{ asset('storage/' . $img->image_path) }}')"
                                 alt="Image {{ $loop->iteration }}">
                        @endforeach
                    </div>
                @endif

                {{-- QR Code --}}
                @if($product->qr_code)
                    <div class="mt-3 text-center">
                        <p class="section-heading">QR Code</p>
                        <img src="{{ asset('storage/' . $product->qr_code) }}"
                             alt="QR Code"
                             class="qr-img">
                        <br>
                        <a href="{{ asset('storage/' . $product->qr_code) }}"
                           download="{{ $product->sku }}_qr.png"
                           class="btn btn-outline-secondary btn-sm mt-2">
                            <i class="mdi mdi-download"></i> Download QR
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ── Right column: details ────────────────────────────── --}}
    <div class="col-md-8 col-lg-9">

        {{-- Basic Info --}}
        <div class="card border-0 shadow-sm grid-margin">
            <div class="card-body">
                <p class="section-heading">Product Details</p>

                <div class="row">
                    <div class="col-sm-8">
                        <h4 class="font-weight-bold mb-1">{{ $product->name }}</h4>
                        <p class="text-muted mb-0" style="font-size:13px;">
                            SKU: <code>{{ $product->sku }}</code>
                            &nbsp;|&nbsp;
                            Category: <strong>{{ $product->category->name ?? '—' }}</strong>
                        </p>
                    </div>
                    <div class="col-sm-4 text-sm-right mt-2 mt-sm-0">
                        <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}"
                              style="font-size:12px;padding:5px 10px;">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($product->is_featured)
                            <span class="badge badge-warning ml-1"
                                  style="font-size:12px;padding:5px 10px;">
                                <i class="mdi mdi-star"></i> Featured
                            </span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row">
                    {{-- Price --}}
                    <div class="col-6 col-md-3 detail-row">
                        <div class="detail-label">Price</div>
                        <div class="detail-value text-primary" style="font-size:18px;font-weight:700;">
                            ₦{{ number_format($product->price, 2) }}
                        </div>
                    </div>

                    {{-- Sale Price --}}
                    <div class="col-6 col-md-3 detail-row">
                        <div class="detail-label">Sale Price</div>
                        <div class="detail-value">
                            @if($product->sale_price)
                                <span class="text-danger" style="font-size:16px;font-weight:700;">
                                    ₦{{ number_format($product->sale_price, 2) }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>

                    {{-- Stock --}}
                    <div class="col-6 col-md-3 detail-row">
                        <div class="detail-label">Stock Qty</div>
                        <div class="detail-value">
                            @php
                                $qty = $product->quantity;
                                $cls = $qty > 10 ? 'badge-stock-ok' : ($qty > 0 ? 'badge-stock-low' : 'badge-stock-out');
                                $lbl = $qty > 10 ? 'In Stock' : ($qty > 0 ? 'Low Stock' : 'Out of Stock');
                            @endphp
                            <span class="badge {{ $cls }}" style="font-size:13px;padding:4px 10px;">
                                {{ $qty }} — {{ $lbl }}
                            </span>
                        </div>
                    </div>

                    {{-- Slug --}}
                    <div class="col-6 col-md-3 detail-row">
                        <div class="detail-label">Slug</div>
                        <div class="detail-value text-muted" style="font-size:12px;word-break:break-all;">
                            {{ $product->slug }}
                        </div>
                    </div>
                </div>

                {{-- Short description --}}
                @if($product->short_description)
                    <div class="detail-row">
                        <div class="detail-label">Short Description</div>
                        <div class="detail-value">{{ $product->short_description }}</div>
                    </div>
                @endif

                {{-- Full description --}}
                @if($product->description)
                    <div class="detail-row">
                        <div class="detail-label">Description</div>
                        <div style="font-size:14px;line-height:1.7;color:#495057;">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif

                {{-- Timestamps --}}
                <div class="row mt-2">
                    <div class="col-6 col-md-4 detail-row">
                        <div class="detail-label">Created</div>
                        <div class="detail-value text-muted" style="font-size:12px;">
                            {{ $product->created_at->format('d M Y, g:ia') }}
                        </div>
                    </div>
                    <div class="col-6 col-md-4 detail-row">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value text-muted" style="font-size:12px;">
                            {{ $product->updated_at->format('d M Y, g:ia') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Reviews --}}
        <div class="card border-0 shadow-sm grid-margin">
            <div class="card-body">
                <p class="section-heading">
                    Customer Reviews
                    <span class="badge badge-secondary ml-1" style="font-size:11px;">
                        {{ $product->reviews->count() }}
                    </span>
                </p>

                @forelse($product->reviews as $review)
                    <div class="review-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong style="font-size:13px;">
                                    {{ $review->user->name ?? 'Guest' }}
                                </strong>
                                <span class="ml-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="mdi mdi-star{{ $i <= $review->rating ? '' : '-outline' }} {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"
                                           style="font-size:13px;"></i>
                                    @endfor
                                </span>
                            </div>
                            <small class="text-muted">
                                {{ $review->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if($review->comment)
                            <p class="mb-0 mt-1" style="font-size:13px;color:#6c757d;">
                                {{ $review->comment }}
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-3">
                        <i class="mdi mdi-comment-outline" style="font-size:2rem;"></i>
                        <p class="mb-0 mt-1" style="font-size:13px;">No reviews yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Danger zone --}}
        <div class="card border-0 shadow-sm grid-margin border-danger" style="border-left: 3px solid #fa5c7c !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="font-weight-bold mb-0" style="font-size:13px;">Delete this product</p>
                    <small class="text-muted">This action cannot be undone. All images and QR codes will also be removed.</small>
                </div>
                <form action="{{ route('admin.products.destroy', $product) }}"
                      method="POST"
                      onsubmit="return confirm('Permanently delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="mdi mdi-trash-can-outline"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function swapImage(thumb, src) {
    document.getElementById('main-image').src = src;
    document.querySelectorAll('.thumb-img').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>
@endpush
