<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;
use App\Helpers\QRCodeHelper;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }
    
    /*
     * now, about the categories, how was it autocreated, it is not yet enough, secondly, let's creat and edit more categories, with sub categories under them
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = ImageHelper::uploadImage(
                $request->file('featured_image'),
                'products',
                800,
                800
            );
        }

        // Create product
        $product = Product::create($validated);

        // Generate QR Code
        $product->qr_code = QRCodeHelper::generateProductQR($product);
        $product->save();

        // Handle additional product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $index => $image) {
                $imagePath = ImageHelper::uploadImage($image, 'products', 800, 800);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'order' => $index + 1,
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
        $categories = Category::where('is_active', true)->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($product->featured_image) {
                ImageHelper::deleteImage($product->featured_image);
            }
            
            $validated['featured_image'] = ImageHelper::uploadImage(
                $request->file('featured_image'),
                'products',
                800,
                800
            );
        }

        // Update product
        $product->update($validated);

        // Handle additional product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $index => $image) {
                $imagePath = ImageHelper::uploadImage($image, 'products', 800, 800);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'order' => $product->images->count() + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete featured image
        if ($product->featured_image) {
            ImageHelper::deleteImage($product->featured_image);
        }

        // Delete product images
        foreach ($product->images as $image) {
            ImageHelper::deleteImage($image->image_path);
        }

        // Delete QR code
        if ($product->qr_code) {
            QRCodeHelper::deleteQRCode($product->qr_code);
        }

        // Delete product
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}