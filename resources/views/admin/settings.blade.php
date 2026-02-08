@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">System Settings</h4>
                    <p class="card-description">Configure your e-commerce settings</p>

                    <div class="alert alert-info" role="alert">
                        <i class="mdi mdi-information mr-2"></i>
                        Settings page is under development. This will include store configuration, 
                        payment gateways, shipping methods, and more.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection