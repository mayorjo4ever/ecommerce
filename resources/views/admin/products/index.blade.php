@extends('admin.layouts.app')

@section('title', 'Products')

@push('styles')
<style>
    /* ── Filter bar ──────────────────────────────────────────── */
    .filter-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
        margin-bottom: 1.25rem;
    }
    .filter-card .card-body {
        padding: 1rem 1.25rem;
    }
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: .625rem;
        align-items: flex-end;
    }
    .filter-row .filter-group {
        flex: 1 1 160px;
        min-width: 140px;
    }
    .filter-row .filter-group label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
        margin-bottom: 4px;
        display: block;
    }
    .filter-row .filter-group .form-control,
    .filter-row .filter-group .custom-select {
        height: 36px;
        font-size: 13px;
        border-radius: 6px;
        border-color: #dee2e6;
    }
    .filter-row .filter-group .form-control:focus,
    .filter-row .filter-group .custom-select:focus {
        border-color: #727cf5;
        box-shadow: 0 0 0 .15rem rgba(114,124,245,.2);
    }
    /* search wrapper with icon */
    .search-wrap { position: relative; }
    .search-wrap .mdi-magnify {
        position: absolute;
        left: 9px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #adb5bd;
        pointer-events: none;
    }
    .search-wrap input { padding-left: 30px; }

    /* clear-filters btn */
    .btn-clear-filters {
        height: 36px;
        font-size: 12px;
        border-radius: 6px;
        white-space: nowrap;
        align-self: flex-end;
    }

    /* result meta */
    #result-meta {
        font-size: 12.5px;
        color: #6c757d;
        margin-bottom: .5rem;
    }

    /* ── Table enhancements ──────────────────────────────────── */
    #products-table-wrap { position: relative; min-height: 120px; }

    /* overlay spinner */
    #table-loader {
        display: none;
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,.7);
        z-index: 10;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }
    #table-loader.active { display: flex; }
    .spinner-ring {
        width: 36px; height: 36px;
        border: 3px solid #dee2e6;
        border-top-color: #727cf5;
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* fade-in for fresh rows */
    @keyframes fadeRow {
        from { opacity: 0; transform: translateY(4px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    #products-table-wrap tbody tr {
        animation: fadeRow .22s ease both;
    }

    /* stock badges */
    .badge-stock-ok      { background: #0acf97; color: #fff; }
    .badge-stock-low     { background: #ffbc00; color: #fff; }
    .badge-stock-out     { background: #fa5c7c; color: #fff; }

    /* active filter pills on the table header */
    .active-filters { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: .6rem; }
    .filter-pill {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; font-weight: 600;
        padding: 3px 8px; border-radius: 20px;
        background: #eef0fd; color: #727cf5;
    }
    .filter-pill .remove-pill {
        cursor: pointer; font-size: 13px; line-height: 1;
        color: #727cf5; border: none; background: none; padding: 0;
    }
</style>
@endpush

@section('content')

{{-- ── Page header ─────────────────────────────────────── --}}
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold mb-0">Products</h4>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="mdi mdi-plus"></i> Add New Product
            </a>
        </div>
    </div>
</div>

{{-- ── Filter bar ───────────────────────────────────────── --}}
<div class="row">
    <div class="col-md-12">
        <div class="card filter-card">
            <div class="card-body">
                <div class="filter-row">

                    {{-- Search --}}
                    <div class="filter-group" style="flex: 2 1 220px;">
                        <label><i class="mdi mdi-magnify"></i> Search</label>
                        <div class="search-wrap">
                            <i class="mdi mdi-magnify"></i>
                            <input type="text"
                                   id="filter-search"
                                   class="form-control"
                                   placeholder="Name or SKU…"
                                   autocomplete="off">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="filter-group">
                        <label>Category</label>
                        <select id="filter-category" class="custom-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="filter-group">
                        <label>Status</label>
                        <select id="filter-status" class="custom-select">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    {{-- Stock level --}}
                    <div class="filter-group">
                        <label>Stock Level</label>
                        <select id="filter-stock" class="custom-select">
                            <option value="">Any</option>
                            <option value="ok">In Stock (&gt; 10)</option>
                            <option value="low">Low (1 – 10)</option>
                            <option value="out">Out of Stock</option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div class="filter-group">
                        <label>Sort By</label>
                        <select id="filter-sort" class="custom-select">
                            <option value="latest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="name_asc">Name A → Z</option>
                            <option value="name_desc">Name Z → A</option>
                            <option value="price_asc">Price Low → High</option>
                            <option value="price_desc">Price High → Low</option>
                            <option value="stock_asc">Stock Low → High</option>
                            <option value="stock_desc">Stock High → Low</option>
                        </select>
                    </div>

                    {{-- Clear --}}
                    <div class="filter-group" style="flex: 0 0 auto;">
                        <label>&nbsp;</label>
                        <button id="btn-clear" class="btn btn-outline-secondary btn-clear-filters">
                            <i class="mdi mdi-close-circle-outline"></i> Clear
                        </button>
                    </div>

                </div>{{-- /filter-row --}}
            </div>
        </div>
    </div>
</div>

{{-- ── Products table ───────────────────────────────────── --}}
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="card-title mb-0">All Products</p>
                    <small id="result-meta"></small>
                </div>

                {{-- Active filter pills --}}
                <div class="active-filters" id="active-pills"></div>

                <div id="products-table-wrap">
                    {{-- Loader overlay --}}
                    <div id="table-loader">
                        <div class="spinner-ring"></div>
                    </div>

                    {{-- Table partial injected here --}}
                    <div id="products-table-container">
                        @include('admin.products._table', ['products' => $products])
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
(function () {
    'use strict';

    /* ── State ─────────────────────────────────────────────── */
    let debounceTimer = null;
    const DEBOUNCE_MS = 380;

    const $search   = document.getElementById('filter-search');
    const $category = document.getElementById('filter-category');
    const $status   = document.getElementById('filter-status');
    const $stock    = document.getElementById('filter-stock');
    const $sort     = document.getElementById('filter-sort');
    const $clear    = document.getElementById('btn-clear');
    const $loader   = document.getElementById('table-loader');
    const $container= document.getElementById('products-table-container');
    const $meta     = document.getElementById('result-meta');
    const $pills    = document.getElementById('active-pills');

    /* ── Helpers ───────────────────────────────────────────── */
    function params(page) {
        const p = new URLSearchParams();
        if ($search.value.trim())   p.set('search',   $search.value.trim());
        if ($category.value)        p.set('category', $category.value);
        if ($status.value !== '')   p.set('status',   $status.value);
        if ($stock.value)           p.set('stock',    $stock.value);
        if ($sort.value)            p.set('sort',     $sort.value);
        if (page)                   p.set('page',     page);
        return p;
    }

    function showLoader()  { $loader.classList.add('active'); }
    function hideLoader()  { $loader.classList.remove('active'); }

    function load(page) {
        showLoader();
        const url = '{{ route('admin.products.index') }}?' + params(page).toString();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            $container.innerHTML = data.html;
            $meta.textContent    = data.meta;
            renderPills();
            bindPagination();
            hideLoader();
        })
        .catch(() => hideLoader());
    }

    /* ── Debounced trigger ─────────────────────────────────── */
    function trigger() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => load(1), DEBOUNCE_MS);
    }

    /* ── Active filter pills ───────────────────────────────── */
    const PILL_LABELS = {
        search:   v => `"${v}"`,
        category: v => $category.options[$category.selectedIndex]?.text,
        status:   v => v === '1' ? 'Active' : 'Inactive',
        stock:    v => ({ ok:'In Stock', low:'Low Stock', out:'Out of Stock' }[v]),
        sort:     v => $sort.options[$sort.selectedIndex]?.text,
    };

    function renderPills() {
        $pills.innerHTML = '';
        const entries = [
            ['search',   $search.value.trim()],
            ['category', $category.value],
            ['status',   $status.value],
            ['stock',    $stock.value],
        ];
        entries.forEach(([key, val]) => {
            if (!val && val !== '0') return;
            const label = PILL_LABELS[key](val);
            const pill = document.createElement('span');
            pill.className = 'filter-pill';
            pill.innerHTML = `${label} <button class="remove-pill" data-key="${key}">×</button>`;
            $pills.appendChild(pill);
        });

        $pills.querySelectorAll('.remove-pill').forEach(btn => {
            btn.addEventListener('click', () => {
                const k = btn.dataset.key;
                if (k === 'search')   { $search.value   = ''; }
                if (k === 'category') { $category.value = ''; }
                if (k === 'status')   { $status.value   = ''; }
                if (k === 'stock')    { $stock.value    = ''; }
                trigger();
            });
        });
    }

    /* ── Pagination (delegated, AJAX) ──────────────────────── */
    function bindPagination() {
        $container.querySelectorAll('.pagination a[href]').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url  = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                load(page);
            });
        });
    }

    /* ── Event listeners ───────────────────────────────────── */
    $search.addEventListener('input',   trigger);
    $category.addEventListener('change', trigger);
    $status.addEventListener('change',  trigger);
    $stock.addEventListener('change',   trigger);
    $sort.addEventListener('change',    trigger);

    $clear.addEventListener('click', () => {
        $search.value   = '';
        $category.value = '';
        $status.value   = '';
        $stock.value    = '';
        $sort.value     = 'latest';
        trigger();
    });

    /* ── Boot ──────────────────────────────────────────────── */
    bindPagination();
    renderPills();
})();
</script>
@endpush