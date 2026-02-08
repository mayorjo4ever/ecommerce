@extends('admin.layouts.app')

@section('title', 'Edit Permission')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Edit Permission</h4>
                    <p class="text-muted">Update permission information</p>
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
                    
                    <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            
                            <small class="form-text text-muted">
                                <strong>Format:</strong> [action] [resource] (e.g., view reports, manage inventory)
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="mdi mdi-information"></i> Permission Usage</h6>
                            <p class="mb-0">
                                <strong>Assigned to {{ $permission->roles->count() }} role(s):</strong><br>
                                @forelse($permission->roles as $role)
                                    <span class="badge badge-info mr-1">{{ $role->name }}</span>
                                @empty
                                    <span class="text-muted">Not assigned to any role</span>
                                @endforelse
                            </p>
                        </div>

                        @if($permission->roles->count() > 0)
                            <div class="alert alert-warning">
                                <i class="mdi mdi-alert"></i>
                                <strong>Warning:</strong> Changing this permission name will affect all roles and users that have this permission.
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update Permission
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-light">Cancel</a>
                        <button type="button" class="btn btn-danger float-right" 
                                onclick="deletePermission({{ $permission->id }})">
                            <i class="mdi mdi-delete"></i> Delete Permission
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-permission-form" action="{{ route('admin.permissions.destroy', $permission->id) }}" 
          method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deletePermission(id) {
        if (confirm('Are you sure you want to delete this permission? This action cannot be undone.')) {
            document.getElementById('delete-permission-form').submit();
        }
    }
</script>
@endpush