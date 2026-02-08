@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Edit Category</h4>
                    <p class="text-muted">Update category information</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Category Information</h4>
                        
                        <div class="form-group">
                            <label for="name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select class="form-control @error('parent_id') is-invalid @enderror" 
                                    id="parent_id" name="parent_id">
                                <option value="">None (Main Category)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" 
                                        {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Display Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', $category->order) }}" min="0">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($category->image)
                        <div class="form-group">
                            <label>Current Image</label>
                            <div>
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" class="img-thumbnail mb-2" style="max-width: 200px;">
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="image">Change Category Image</label>
                            <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <small class="form-text text-muted">Leave empty to keep current image</small>
                            @error('image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            
                            <!-- New Image Preview -->
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <!-- Status -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Status</h4>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_active" value="1" 
                                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    Active
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Stats -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Category Statistics</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Total Products:</strong><br>
                                <small>{{ $category->products->count() }}</small>
                            </li>
                            <li class="mb-2">
                                <strong>Subcategories:</strong><br>
                                <small>{{ $category->children->count() }}</small>
                            </li>
                            <li class="mb-2">
                                <strong>Created:</strong><br>
                                <small>{{ $category->created_at->format('d M, Y') }}</small>
                            </li>
                            <li class="mb-2">
                                <strong>Last Updated:</strong><br>
                                <small>{{ $category->updated_at->format('d M, Y') }}</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="mdi mdi-content-save"></i> Update Category
                        </button> <br/> <br/>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light text-dark font-weight-bold btn-block">
                            Cancel
                        </a><br/> <br/>
                        <button type="button" class="btn btn-danger text-white font-weight-bold btn-block" 
                                onclick="deleteCategory({{ $category->id }})">
                            <i class="mdi mdi-delete"></i> Delete Category
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (hidden) -->
    <form id="delete-category-form" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    // Preview image
    function previewImage(event) {
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
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

    // Delete category
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            document.getElementById('delete-category-form').submit();
        }
    }
</script>
@endpush