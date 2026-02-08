@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Categories</h4>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Add New Category
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">All Categories</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">Order</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <!-- Parent Category -->
                                    <tr class="table-light">
                                        <td><strong>{{ $category->order }}</strong></td>
                                        <td>
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" 
                                                     alt="{{ $category->name }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; border-radius: 5px;">
                                                    <i class="mdi mdi-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $category->products->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                               class="btn btn-sm btn-info" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteCategory({{ $category->id }})" title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Subcategories -->
                                    @if($category->children->count() > 0)
                                        @foreach($category->children as $subcategory)
                                            <tr>
                                                <td class="pl-4">{{ $subcategory->order }}</td>
                                                <td>
                                                    @if($subcategory->image)
                                                        <img src="{{ asset('storage/' . $subcategory->image) }}" 
                                                             alt="{{ $subcategory->name }}" 
                                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; border-radius: 5px;">
                                                            <i class="mdi mdi-image text-muted" style="font-size: 14px;"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="pl-4">
                                                    <i class="mdi mdi-subdirectory-arrow-right text-muted"></i>
                                                    {{ $subcategory->name }}
                                                </td>
                                                <td>{{ $subcategory->slug }}</td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ $subcategory->products->count() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $subcategory->is_active ? 'success' : 'secondary' }}">
                                                        {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.categories.edit', $subcategory->id) }}" 
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteCategory({{ $subcategory->id }})" title="Delete">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No categories found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (hidden) -->
    <form id="delete-category-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            const form = document.getElementById('delete-category-form');
            form.action = '/admin/categories/' + id;
            form.submit();
        }
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush