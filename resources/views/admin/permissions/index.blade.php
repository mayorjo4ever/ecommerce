@extends('admin.layouts.app')

@section('title', 'Permissions')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">All Permissions</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPermissionModal">
                    <i class="mdi mdi-plus"></i> Add New Permission
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

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
        @foreach($permissions as $group => $groupPermissions)
            <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">
                            <i class="mdi mdi-folder-outline"></i> {{ $group }} 
                            <span class="badge badge-info ml-2">{{ $groupPermissions->count() }}</span>
                        </h5>
                        
                        <div class="mt-3">
                            @foreach($groupPermissions as $permission)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge badge-dark">{{ $permission->name }}</span>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deletePermission({{ $permission->id }})"
                                            title="Delete">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Permission</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="e.g., view reports, create invoices" required>
                            <small class="form-text text-muted">
                                Format: [action] [resource]<br>
                                Examples: view products, create orders, delete users
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i>
                            <small>This permission will be automatically assigned to Super Admin role.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Create Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form (hidden) -->
    <form id="delete-permission-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deletePermission(id) {
        if (confirm('Are you sure you want to delete this permission? This action cannot be undone.')) {
            const form = document.getElementById('delete-permission-form');
            form.action = '/admin/permissions/' + id;
            form.submit();
        }
    }

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush