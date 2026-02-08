@extends('admin.layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Edit Role: {{ $role->name }}</h4>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back
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

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Role Information</h4>
                        
                        <div class="form-group">
                            <label for="name">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $role->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <strong>Users with this role:</strong><br>
                            <span class="badge badge-info">{{ $role->users()->count() }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="mdi mdi-content-save"></i> Update Role
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light btn-block">Cancel</a>
                        <button type="button" class="btn btn-danger btn-block" 
                                onclick="deleteRole({{ $role->id }})">
                            <i class="mdi mdi-delete"></i> Delete Role
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Assign Permissions <span class="text-danger">*</span></h4>
                        
                        @error('permissions')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-check mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="select-all">
                                Select All Permissions
                                <i class="input-helper"></i>
                            </label>
                        </div>

                        <hr>

                        @foreach($permissions as $group => $groupPermissions)
                            <div class="mb-4">
                                <h5 class="text-capitalize mb-3">
                                    <i class="mdi mdi-folder-outline"></i> {{ $group }} Management
                                </h5>
                                <div class="row">
                                    @foreach($groupPermissions as $permission)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input permission-checkbox" 
                                                           name="permissions[]" value="{{ $permission->id }}"
                                                           {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    {{ $permission->name }}
                                                    <i class="input-helper"></i>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="delete-role-form" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    // Select/Deselect all permissions
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Update select-all checkbox based on individual checkboxes
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.permission-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
            document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
        });
    });

    // Check if all permissions are selected on page load
    window.addEventListener('load', function() {
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
        document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
    });

    function deleteRole(id) {
        if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            document.getElementById('delete-role-form').submit();
        }
    }
</script>
@endpush