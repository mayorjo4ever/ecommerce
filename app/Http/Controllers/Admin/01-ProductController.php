<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Helpers\ImageHelper;
use App\Helpers\QRCodeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * List products with optional AJAX filtering.
     *
     * Filters accepted (all optional, via query-string):
     *   search   – matches name or SKU (case-insensitive, partial)
     *   category – category_id (integer)
     *   status   – 1 = active, 0 = inactive
     *   stock    – 'ok' (>10) | 'low' (1–10) | 'out' (0)
     *   sort     – latest | oldest | name_asc | name_desc |
     *              price_asc | price_desc | stock_asc | stock_desc
     *
     * AJAX responses return JSON { html, meta }.
     * Non-AJAX (normal page load) returns the full view.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // ── Search (name or SKU) ──────────────────────────────
        if ($search = trim($request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku',  'like', '%' . $search . '%');
            });
        }

        // ── Category ──────────────────────────────────────────
        if ($categoryId = $request->get('category')) {
            $query->where('category_id', (int) $categoryId);
        }

        // ── Status ────────────────────────────────────────────
        if ($request->filled('status')) {
            $query->where('is_active', (bool) $request->get('status'));
        }

        // ── Stock level ───────────────────────────────────────
        switch ($request->get('stock')) {
            case 'ok':
                $query->where('quantity', '>', 10);
                break;
            case 'low':
                $query->whereBetween('quantity', [1, 10]);
                break;
            case 'out':
                $query->where('quantity', 0);
                break;
        }

        // ── Sort ──────────────────────────────────────────────
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('quantity', 'desc');
                break;
            default: // 'latest'
                $query->latest();
        }

        $products = $query->paginate(20)->withQueryString();

        // ── AJAX response ─────────────────────────────────────
        if ($request->ajax()) {
            $total = $products->total();
            $meta  = $total === 0
                ? 'No products found'
                : number_format($total) . ' product' . ($total === 1 ? '' : 's') . ' found';

            return response()->json([
                'html' => view('admin.products._table', compact('products'))->render(),
                'meta' => $meta,
            ]);
        }

        // ── Full page load ─────────────────────────────────────
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    // ─────────────────────────────────────────────────────────────
    // The rest of the controller is unchanged
    // ─────────────────────────────────────────────────────────────

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'required|string|unique:products,sku',
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'quantity'          => 'required|integer|min:0',
            'featured_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // In store():
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
        ]);

        $validated['slug']        = Str::slug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = ImageHelper::uploadImage(
                $request->file('featured_image'), 'products', 800, 800
            );
        }

        $product = Product::create($validated);

        $product->qr_code = QRCodeHelper::generateProductQR($product);
        $product->save();

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $index => $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => ImageHelper::uploadImage($image, 'products', 800, 800),
                    'order'      => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'required|string|unique:products,sku,' . $product->id,
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'quantity'          => 'required|integer|min:0',
            'featured_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // In update():
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
        ]);

        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) {
                ImageHelper::deleteImage($product->featured_image);
            }
            $validated['featured_image'] = ImageHelper::uploadImage(
                $request->file('featured_image'), 'products', 800, 800
            );
        }

        $product->update($validated);

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $index => $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => ImageHelper::uploadImage($image, 'products', 800, 800),
                    'order'      => $product->images()->count() + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->featured_image) {
            ImageHelper::deleteImage($product->featured_image);
        }

        foreach ($product->images as $image) {
            ImageHelper::deleteImage($image->image_path);
        }

        if ($product->qr_code) {
            QRCodeHelper::deleteQRCode($product->qr_code);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function checkBarcode(Request $request): \Illuminate\Http\JsonResponse
    {
        $barcode   = trim($request->get('barcode', ''));
        $excludeId = $request->get('exclude'); // current product id on edit

        if (empty($barcode)) {
            return response()->json(['taken' => false]);
        }

        $query = \App\Models\Product::where('barcode', $barcode);

        if ($excludeId) {
            $query->where('id', '!=', (int) $excludeId);
        }

        $existing = $query->first();

        return response()->json([
            'taken'   => (bool) $existing,
            'product' => $existing?->name,
        ]);
    }
}