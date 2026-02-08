@extends('admin.layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Roles & Permissions</h4>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Create New Role
                </a>
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
        @foreach($roles as $role)
            <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">{{ $role->name }}</h4>
                            @if($role->name === 'Super Admin')
                                <span class="badge badge-danger">Protected</span>
                            @endif
                        </div>
                        
                        <p class="text-muted">
                            <i class="mdi mdi-account-multiple"></i> 
                            {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                        </p>
                        
                        <p class="text-muted">
                            <i class="mdi mdi-shield-check"></i> 
                            {{ $role->permissions->count() }} {{ Str::plural('permission', $role->permissions->count()) }}
                        </p>
                        
                        <div class="mt-3">
                            <strong>Permissions:</strong>
                            <div class="mt-2" style="max-height: 150px; overflow-y: auto;">
                                @foreach($role->permissions->take(10) as $permission)
                                    <span class="badge badge-dark mb-1">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 10)
                                    <span class="text-muted small">
                                        +{{ $role->permissions->count() - 10 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.roles.show', $role->id) }}" 
                               class="btn btn-sm btn-info">
                                <i class="mdi mdi-eye"></i> View
                            </a>
                            @if($role->name !== 'Super Admin')
                                <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="deleteRole({{ $role->id }})">
                                    <i class="mdi mdi-delete"></i> Delete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form id="delete-role-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deleteRole(id) {
        if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            const form = document.getElementById('delete-role-form');
            form.action = '/admin/roles/' + id;
            form.submit();
        }
    }

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush