@extends('admin.layouts.app')

@section('title', 'Role Details')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Role: {{ $role->name }}</h4>
                <div>
                    @if($role->name !== 'Super Admin')
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Role Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Role Information</h4>
                    
                    <div class="mb-3">
                        <strong>Role Name:</strong><br>
                        {{ $role->name }}
                        @if($role->name === 'Super Admin')
                            <span class="badge badge-danger ml-2">Protected</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Total Users:</strong><br>
                        <span class="badge badge-info">{{ $role->users_count }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Total Permissions:</strong><br>
                        <span class="badge badge-success">{{ $role->permissions->count() }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        {{ $role->created_at->format('d M, Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Assigned Permissions</h4>
                    
                    @php
                        $groupedPermissions = $role->permissions->groupBy(function($permission) {
                            return explode(' ', $permission->name)[1] ?? 'other';
                        });
                    @endphp

                    @forelse($groupedPermissions as $group => $permissions)
                        <div class="mb-4">
                            <h5 class="text-capitalize">
                                <i class="mdi mdi-folder-outline"></i> {{ $group }} Management
                            </h5>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-6 mb-2">
                                        <span class="badge badge-dark">
                                            <i class="mdi mdi-check text-success"></i>
                                            {{ $permission->name }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @empty
                        <p class="text-muted">No permissions assigned to this role.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection