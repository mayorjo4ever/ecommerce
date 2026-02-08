@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Create New Category</h4>
                    <p class="text-muted">Add a new category or subcategory</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Category Information</h4>
                        
                        <div class="form-group">
                            <label for="name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
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
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Leave as "None" to create a main category, or select a parent to create a subcategory</small>
                            @error('parent_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Display Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            <small class="form-text text-muted">Lower numbers appear first</small>
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Category Image</label>
                            <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <small class="form-text text-muted">Recommended size: 800x800px (JPEG, PNG, JPG, GIF - Max 2MB)</small>
                            @error('image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            
                            <!-- Image Preview -->
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
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    Active
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                            <small class="form-text text-muted">Enable this category in store</small>
                        </div>
                    </div>
                </div>

                <!-- Information -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Information</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="mdi mdi-information text-primary"></i>
                                <small>Slug will be auto-generated from name</small>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-folder-multiple text-primary"></i>
                                <small>You can create unlimited subcategories</small>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-sort-numeric text-primary"></i>
                                <small>Use order to control display sequence</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="mdi mdi-content-save"></i> Create Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-block">
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
</script>
@endpush