@extends('admin.layouts.app')

@section('title', 'Permissions')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Permissions Management</h4>
                    <p class="text-muted">Manage system permissions</p>
                </div>
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Add New Permission
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
        @forelse($permissions as $group => $groupPermissions)
            <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize mb-3">
                            <i class="mdi mdi-shield-check text-primary"></i> {{ $group }} 
                            <span class="badge badge-info ml-2">{{ $groupPermissions->count() }}</span>
                        </h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    @foreach($groupPermissions as $permission)
                                        <tr>
                                            <td>
                                                <span class="badge badge-dark">{{ $permission->name }}</span>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.permissions.edit', $permission->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deletePermission({{ $permission->id }})"
                                                        title="Delete">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-muted">No permissions found. Create your first permission!</p>
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Create Permission
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
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