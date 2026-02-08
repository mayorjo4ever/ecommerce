@extends('admin.layouts.app')

@section('title', 'Create Product')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/vendors/select2/select2.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Create New Product</h4>
                    <p class="text-muted">Add a new product to your store</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
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
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" value="{{ old('sku') }}" required>
                            <small class="form-text text-muted">Stock Keeping Unit - unique identifier</small>
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                      id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
                            <small class="form-text text-muted">Brief product description (max 500 characters)</small>
                            @error('short_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Full Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6">{{ old('description') }}</textarea>
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
                                           id="price" name="price" value="{{ old('price') }}" required>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_price">Sale Price (₦) <small>Promo Price / Least Price</small></label>
                                    <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                           id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                    <small class="form-text text-muted">Leave empty if no sale</small>
                                    @error('sale_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', 0) }}" required>
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
                        
                        <div class="form-group">
                            <label for="featured_image">Featured Image</label>
                            <input type="file" class="form-control-file @error('featured_image') is-invalid @enderror" 
                                   id="featured_image" name="featured_image" accept="image/*" onchange="previewFeaturedImage(event)">
                            <small class="form-text text-muted">Main product image (JPEG, PNG, JPG, GIF - Max 2MB)</small>
                            @error('featured_image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="featured-preview" class="mt-3" style="display: none;">
                                <img id="featured-preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="product_images">Additional Images</label>
                            <input type="file" class="form-control-file @error('product_images.*') is-invalid @enderror" 
                                   id="product_images" name="product_images[]" accept="image/*" multiple onchange="previewAdditionalImages(event)">
                            <small class="form-text text-muted">Upload multiple images (JPEG, PNG, JPG, GIF - Max 2MB each)</small>
                            @error('product_images.*')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            
                            <!-- Images Preview -->
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
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    Active
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                            <small class="form-text text-muted">Enable this product in store</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_featured" value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    Featured Product
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                            <small class="form-text text-muted">Show in featured section</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Information</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="mdi mdi-information text-primary"></i>
                                <small>QR code will be generated automatically</small>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-link-variant text-primary"></i>
                                <small>Product slug will be auto-generated from name</small>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-image-multiple text-primary"></i>
                                <small>Images will be optimized automatically</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="mdi mdi-content-save"></i> Create Product
                        </button> <br/>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-block">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
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

    // Auto-generate SKU from product name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const sku = 'SKU-' + name.substring(0, 3).toUpperCase() + '-' + Date.now().toString().slice(-6);
        document.getElementById('sku').value = sku;
    });

    // Validate sale price is less than regular price
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