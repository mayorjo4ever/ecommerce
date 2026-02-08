@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Admin Users</h4>
                <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Add New Admin
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
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
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">All Admin Users</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                    <tr>
                                        <td>
                                            {{ $admin->name }}
                                            @if($admin->id === auth()->guard('admin')->id())
                                                <span class="badge badge-primary ml-2">You</span>
                                            @endif
                                        </td>
                                        <td>{{ $admin->email }}</td>
                                        <td>
                                            @foreach($admin->roles as $role)
                                                <span class="badge badge-{{ $role->name === 'Super Admin' ? 'danger' : 'info' }}">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>{{ $admin->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.admins.show', $admin->id) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($admin->id !== auth()->guard('admin')->id())
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteAdmin({{ $admin->id }})" title="Delete">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No admins found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $admins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-admin-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deleteAdmin(id) {
        if (confirm('Are you sure you want to delete this admin?')) {
            const form = document.getElementById('delete-admin-form');
            form.action = '/admin/admins/' + id;
            form.submit();
        }
    }

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush