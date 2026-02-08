@extends('admin.layouts.app')

@section('title', 'Admin Details')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Admin User Details</h4>
                <div>
                    @if($admin->id !== auth()->guard('admin')->id())
                        <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Admin Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Admin Information</h4>
                    
                    <div class="mb-3">
                        <strong>Name:</strong><br>
                        {{ $admin->name }}
                        @if($admin->id === auth()->guard('admin')->id())
                            <span class="badge badge-primary ml-2">You</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        {{ $admin->email }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        {{ $admin->created_at->format('d M, Y H:i') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        {{ $admin->updated_at->format('d M, Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles & Permissions -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Roles & Permissions</h4>
                    
                    <div class="mb-4">
                        <strong>Assigned Roles:</strong><br>
                        @forelse($admin->roles as $role)
                            <span class="badge badge-{{ $role->name === 'Super Admin' ? 'danger' : 'info' }} mr-1 mb-1">
                                {{ $role->name }}
                            </span>
                        @empty
                            <span class="text-muted">No roles assigned</span>
                        @endforelse
                    </div>
                    
                    <div>
                        <strong>Permissions:</strong><br>
                        <div class="mt-2">
                            @forelse($admin->getAllPermissions()->groupBy(function($permission) {
                                return explode(' ', $permission->name)[1] ?? 'other';
                            }) as $group => $permissions)
                                <div class="mb-2">
                                    <strong class="text-capitalize">{{ $group }}:</strong><br>
                                    @foreach($permissions as $permission)
                                        <span class="badge badge-light mr-1 mb-1">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @empty
                                <span class="text-muted">No permissions assigned</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection