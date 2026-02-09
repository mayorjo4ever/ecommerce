<?php use Illuminate\Support\Facades\Session;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Skydash CSS -->
    <link rel="stylesheet" href="{{ asset('admin/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}">
    
    @stack('styles')
    
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png') }}" />
</head>
<body>
    <div class="container-scroller">
        
        @include('admin.partials.navbar')
        
        <div class="container-fluid page-body-wrapper">
            
            @include('admin.partials.sidebar')
            
            <div class="main-panel">
                <div class="content-wrapper">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    @yield('content')
                    
                </div>
                
                @include('admin.partials.footer')
                
            </div>
        </div>
    </div>

    <!-- Skydash JS -->
   <!-- Plugin js -->
<script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>

<!-- Plugin js for this page -->
@stack('plugin-scripts')

<!-- Custom js for this page -->
<script src="{{ asset('admin/js/off-canvas.js') }}"></script>
<script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('admin/js/template.js') }}"></script>
<script src="{{ asset('admin/js/settings.js') }}"></script>
<script src="{{ asset('admin/js/todolist.js') }}"></script>

@stack('custom-scripts')
    <!-- Make sure jQuery and Bootstrap are loaded -->
    @if(!isset($skipBootstrap))
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @endif
    
    @if( Session::get('page')=='pos')
    <!-- POS Main Script -->
    <script src="{{ asset('admin/js/pos.js') }}"></script>
    @endif 

    
    @stack('scripts')
    
 
</body>
</html>