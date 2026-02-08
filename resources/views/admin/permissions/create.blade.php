@extends('admin.layouts.app')

@section('title', 'Create Permission')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Create New Permission</h4>
                    <p class="text-muted">Add a new permission to the system</p>
                </div>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Permissions
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Permission Information</h4>
                    
                    <form action="{{ route('admin.permissions.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., view reports, create invoices, manage warehouse" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            
                            <small class="form-text text-muted">
                                <strong>Naming Convention:</strong> [action] [resource]<br>
                                <strong>Examples:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>view products, create products, edit products, delete products</li>
                                    <li>view orders, process orders, export orders</li>
                                    <li>view reports, export reports</li>
                                    <li>manage settings, manage warehouse, manage inventory</li>
                                </ul>
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i>
                            <strong>Note:</strong> This permission will be automatically assigned to the Super Admin role.
                        </div>

                        <hr>

                        <h5 class="mb-3">Quick Add Common Permissions</h5>
                        <p class="text-muted">Click on any suggestion to auto-fill the input field:</p>
                        
                        <div class="mb-3">
                            <strong>Inventory:</strong><br>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('view inventory')">view inventory</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('update inventory')">update inventory</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('manage stock')">manage stock</button>
                        </div>

                        <div class="mb-3">
                            <strong>Shipping:</strong><br>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('view shipping')">view shipping</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('create shipping')">create shipping</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('manage shipping')">manage shipping</button>
                        </div>

                        <div class="mb-3">
                            <strong>Reports:</strong><br>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('view reports')">view reports</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('export reports')">export reports</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('view analytics')">view analytics</button>
                        </div>

                        <div class="mb-3">
                            <strong>System:</strong><br>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('view logs')">view logs</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('clear cache')">clear cache</button>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPermission('manage backups')">manage backups</button>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Create Permission
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    function setPermission(permissionName) {
        document.getElementById('name').value = permissionName;
        document.getElementById('name').focus();
    }
</script>
@endpush