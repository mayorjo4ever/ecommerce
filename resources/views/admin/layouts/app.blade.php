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
    <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>
    <script src="{{ asset('admin/js/settings.js') }}"></script>
    <script src="{{ asset('admin/js/custom.js') }}"></script>
    <script src="{{ asset('admin/js/todolist.js') }}"></script>
    
    @stack('scripts')
    
  <script>
$(document).ready(function() {
    // Handle sidebar menu collapse
    $('.sidebar .nav-item [data-toggle="collapse"]').on('click', function(e) {
        e.preventDefault();
        
        var $this = $(this);
        var target = $this.attr('href');
        var $parentNavItem = $this.closest('.nav-item');
        
        // Check if this menu is currently open
        var isOpen = $(target).hasClass('show');
        
        // Close all collapse items and remove active class from all nav-items
        $('.sidebar .collapse').removeClass('show');
        $('.sidebar .nav-item').removeClass('active');
        
        // If it wasn't open, open it and add active class
        if (!isOpen) {
            $(target).addClass('show');
            $parentNavItem.addClass('active');
        }
    });
    
    // Add active class based on current page URL
    var current_url = window.location.pathname;
    var found = false;
    
    // First, check sub-menu items (more specific)
    $('.sidebar .sub-menu .nav-link').each(function() {
        var link_url = $(this).attr('href');
        
        if (link_url && link_url !== '#' && current_url === link_url) {
            found = true;
            
            // Remove all active classes first
            $('.sidebar .nav-item').removeClass('active');
            $('.sidebar .nav-link').removeClass('active');
            $('.sidebar .collapse').removeClass('show');
            
            // Add active to this specific link
            $(this).addClass('active');
            
            // Open parent collapse and mark parent nav-item as active
            $(this).closest('.collapse').addClass('show');
            $(this).closest('.collapse').prev('[data-toggle="collapse"]').closest('.nav-item').addClass('active');
            
            return false; // Break the loop
        }
    });
    
    // If not found in sub-menus, check main menu items
    if (!found) {
        $('.sidebar > .nav > .nav-item > .nav-link').not('[data-toggle="collapse"]').each(function() {
            var link_url = $(this).attr('href');
            
            if (link_url && link_url !== '#' && current_url === link_url) {
                found = true;
                
                // Remove all active classes first
                $('.sidebar .nav-item').removeClass('active');
                $('.sidebar .nav-link').removeClass('active');
                
                // Add active to this item
                $(this).addClass('active');
                $(this).closest('.nav-item').addClass('active');
                
                return false; // Break the loop
            }
        });
    }
    
    // If still not found, try partial matching for resource routes (like /admin/products/1/edit)
    if (!found) {
        var url_parts = current_url.split('/').filter(function(part) {
            return part !== '';
        });
        
        if (url_parts.length >= 2) {
            var resource_name = url_parts[1]; // e.g., 'products', 'categories', 'orders'
            
            $('.sidebar .sub-menu .nav-link').each(function() {
                var link_url = $(this).attr('href');
                
                if (link_url && link_url.indexOf('/' + resource_name) !== -1) {
                    // Remove all active classes first
                    $('.sidebar .nav-item').removeClass('active');
                    $('.sidebar .nav-link').removeClass('active');
                    $('.sidebar .collapse').removeClass('show');
                    
                    // Add active to this link
                    $(this).addClass('active');
                    
                    // Open parent collapse and mark parent nav-item as active
                    $(this).closest('.collapse').addClass('show');
                    $(this).closest('.collapse').prev('[data-toggle="collapse"]').closest('.nav-item').addClass('active');
                    
                    return false; // Break the loop
                }
            });
        }
    }
});
</script>
</body>
</html>