@extends('admin.layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Admin Profile</h4>
                    <p class="card-description">View and update your profile information</p>

                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" 
                                   value="{{ auth()->guard('admin')->user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" 
                                   value="{{ auth()->guard('admin')->user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" class="form-control" id="role" 
                                   value="{{ auth()->guard('admin')->user()->role_names }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="created">Member Since</label>
                            <input type="text" class="form-control" id="created" 
                                   value="{{ auth()->guard('admin')->user()->created_at->format('d M, Y') }}" readonly>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection