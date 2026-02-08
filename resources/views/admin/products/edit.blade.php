@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Edit Product</h4>
                    <p class="text-muted">Update product information</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Basic Information</h4>
                        
                        <div class="form-group">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
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

                        <div class="form-group">
                            <label for="short_description">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                      id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Full Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Pricing & Inventory</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Regular Price (₦) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_price">Sale Price (₦)</label>
                                    <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                           id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                                    @error('sale_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                            @error('quantity')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Product Images</h4>
                        
                        <!-- Current Featured Image -->
                        @if($product->featured_image)
                        <div class="form-group">
                            <label>Current Featured Image</label>
                            <div>
                                <img src="{{ asset('storage/' . $product->featured_image) }}" 
                                     alt="{{ $product->name }}" class="img-thumbnail mb-2" style="max-width: 200px;">
                            </div>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="featured_image">Change Featured Image</label>
                            <input type="file" class="form-control-file @error('featured_image') is-invalid @enderror" 
                                   id="featured_image" name="featured_image" accept="image/*" onchange="previewFeaturedImage(event)">
                            <small class="form-text text-muted">Leave empty to keep current image</small>
                            @error('featured_image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            
                            <!-- New Image Preview -->
                            <div id="featured-preview" class="mt-3" style="display: none;">
                                <img id="featured-preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <!-- Current Additional Images -->
                        @if($product->images->count() > 0)
                        <div class="form-group">
                            <label>Current Additional Images</label>
                            <div class="row">
                                @foreach($product->images as $image)
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="Product Image" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                                style="top: 5px; right: 20px;" 
                                                onclick="deleteProductImage({{ $image->id }})">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="product_images">Add More Images</label>
                            <input type="file" class="form-control-file" 
                                   id="product_images" name="product_images[]" accept="image/*" multiple onchange="previewAdditionalImages(event)">
                            @error('product_images.*')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            
                            <!-- New Images Preview -->
                            <div id="additional-preview" class="mt-3 row"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <!-- Status & Visibility -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Status & Visibility</h4>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_active" value="1" 
                                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    Active
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_featured" value="1" 
                                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    Featured Product
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Product Information</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Created:</strong><br>
                                <small>{{ $product->created_at->format('d M, Y H:i') }}</small>
                            </li>
                            <li class="mb-2">
                                <strong>Last Updated:</strong><br>
                                <small>{{ $product->updated_at->format('d M, Y H:i') }}</small>
                            </li>
                            <li class="mb-2">
                                <strong>Total Sales:</strong><br>
                                <small>{{ $product->orderItems->sum('quantity') ?? 0 }} units</small>
                            </li>
                            @if($product->qr_code)
                            <li class="mb-2">
                                <strong>QR Code:</strong><br>
                                <img src="{{ asset('storage/' . $product->qr_code) }}" 
                                     alt="QR Code" style="width: 100px; height: 100px;">
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="mdi mdi-content-save"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-block">
                            Cancel
                        </a>
                        <button type="button" class="btn btn-danger btn-block" 
                                onclick="deleteProduct({{ $product->id }})">
                            <i class="mdi mdi-delete"></i> Delete Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (hidden) -->
    <form id="delete-product-form" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    // Preview featured image
    function previewFeaturedImage(event) {
        const preview = document.getElementById('featured-preview');
        const previewImg = document.getElementById('featured-preview-img');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }

    // Preview additional images
    function previewAdditionalImages(event) {
        const preview = document.getElementById('additional-preview');
        preview.innerHTML = '';
        const files = event.target.files;
        
        if (files) {
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-6 mb-3';
                    col.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" 
                             class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                    `;
                    preview.appendChild(col);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // Delete product
    function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            document.getElementById('delete-product-form').submit();
        }
    }

    // Delete product image (AJAX - we'll implement this next)
    function deleteProductImage(imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            // TODO: Implement AJAX delete
            alert('Image deletion will be implemented via AJAX');
        }
    }

    // Validate sale price
    document.getElementById('sale_price').addEventListener('input', function() {
        const regularPrice = parseFloat(document.getElementById('price').value) || 0;
        const salePrice = parseFloat(this.value) || 0;
        
        if (salePrice > 0 && salePrice >= regularPrice) {
            this.setCustomValidity('Sale price must be less than regular price');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endpush