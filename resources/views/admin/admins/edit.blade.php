@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Edit Admin User</h4>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
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

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Admin Information</h4>
                    
                    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>
                        <p class="text-muted">Leave password fields empty to keep current password</p>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            <small class="form-text text-muted">Minimum 8 characters</small>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Assign Roles <span class="text-danger">*</span></label>
                            @error('roles')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" 
                                               name="roles[]" value="{{ $role->id }}"
                                               {{ in_array($role->id, old('roles', $admin->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        {{ $role->name }}
                                        <i class="input-helper"></i>
                                    </label>
                                    <small class="form-text text-muted">
                                        {{ $role->permissions->count() }} permissions
                                    </small>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update Admin
                        </button>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-light">Cancel</a>
                        <button type="button" class="btn btn-danger float-right" 
                                onclick="deleteAdmin({{ $admin->id }})">
                            <i class="mdi mdi-delete"></i> Delete Admin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-admin-form" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deleteAdmin(id) {
        if (confirm('Are you sure you want to delete this admin? This action cannot be undone.')) {
            document.getElementById('delete-admin-form').submit();
        }
    }
</script>
@endpush