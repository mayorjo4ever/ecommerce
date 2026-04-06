<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- Dashboard --}}
        <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- Point of Sale --}}
        <li class="nav-item {{ Route::is('admin.pos.*') ? 'active' : '' }}">
            <a class="nav-link {{ Route::is('admin.pos.*') ? '' : 'collapsed' }}"
               data-toggle="collapse" href="#pos-menu"
               aria-expanded="{{ Route::is('admin.pos.*') ? 'true' : 'false' }}"
               aria-controls="pos-menu">
                <i class="icon-basket menu-icon"></i>
                <span class="menu-title">Point of Sale</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Route::is('admin.pos.*') ? 'show' : '' }}" id="pos-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.pos.index') ? 'active' : '' }}"
                           href="{{ route('admin.pos.index') }}">New Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.pos.history') ? 'active' : '' }}"
                           href="{{ route('admin.pos.history') }}">Sales History</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Products --}}
        <li class="nav-item {{ Route::is('admin.products.*') ? 'active' : '' }}">
            <a class="nav-link {{ Route::is('admin.products.*') ? '' : 'collapsed' }}"
               data-toggle="collapse" href="#products-menu"
               aria-expanded="{{ Route::is('admin.products.*') ? 'true' : 'false' }}"
               aria-controls="products-menu">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Route::is('admin.products.*') ? 'show' : '' }}" id="products-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.products.index') ? 'active' : '' }}"
                           href="{{ route('admin.products.index') }}">All Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.products.create') ? 'active' : '' }}"
                           href="{{ route('admin.products.create') }}">Add Product</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Stock Movements --}}
        <li class="nav-item {{ Route::is('admin.stock.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.stock.report') }}">
                <i class="mdi mdi-swap-vertical menu-icon"></i>
                <span class="menu-title">Stock Movements</span>
            </a>
        </li>

        {{-- Categories --}}
        <li class="nav-item {{ Route::is('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link {{ Route::is('admin.categories.*') ? '' : 'collapsed' }}"
               data-toggle="collapse" href="#categories-menu"
               aria-expanded="{{ Route::is('admin.categories.*') ? 'true' : 'false' }}"
               aria-controls="categories-menu">
                <i class="icon-columns menu-icon"></i>
                <span class="menu-title">Categories</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Route::is('admin.categories.*') ? 'show' : '' }}" id="categories-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.categories.index') ? 'active' : '' }}"
                           href="{{ route('admin.categories.index') }}">All Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.categories.create') ? 'active' : '' }}"
                           href="{{ route('admin.categories.create') }}">Add Category</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Orders --}}
        <li class="nav-item {{ Route::is('admin.orders.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.orders.index') }}">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Orders</span>
            </a>
        </li>

        {{-- Customers --}}
        <li class="nav-item {{ Route::is('admin.customers.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.customers.index') }}">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Customers</span>
            </a>
        </li>

        {{-- Coupons --}}
        <li class="nav-item {{ Route::is('admin.coupons.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                <i class="icon-tag menu-icon"></i>
                <span class="menu-title">Coupons</span>
            </a>
        </li>

        {{-- Reviews --}}
        <li class="nav-item {{ Route::is('admin.reviews.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                <i class="icon-star menu-icon"></i>
                <span class="menu-title">Reviews</span>
            </a>
        </li>

        {{-- Admin Management --}}
        @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->canManageAdmins())
        @php
            $adminMenuActive = Route::is('admin.admins.*') || Route::is('admin.roles.*') || Route::is('admin.permissions.*');
        @endphp
        <li class="nav-item {{ $adminMenuActive ? 'active' : '' }}">
            <a class="nav-link {{ $adminMenuActive ? '' : 'collapsed' }}"
               data-toggle="collapse" href="#admins-menu"
               aria-expanded="{{ $adminMenuActive ? 'true' : 'false' }}"
               aria-controls="admins-menu">
                <i class="icon-lock menu-icon"></i>
                <span class="menu-title">Admin Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ $adminMenuActive ? 'show' : '' }}" id="admins-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.admins.*') ? 'active' : '' }}"
                           href="{{ route('admin.admins.index') }}">All Admins</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.roles.*') ? 'active' : '' }}"
                           href="{{ route('admin.roles.index') }}">Roles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.permissions.*') ? 'active' : '' }}"
                           href="{{ route('admin.permissions.index') }}">Permissions</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        {{-- Reports --}}
        <li class="nav-item {{ Route::is('admin.reports.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.reports.index') }}">
                <i class="icon-chart menu-icon"></i>
                <span class="menu-title">Reports</span>
            </a>
        </li>

        {{-- Stock Taking --}}
        <li class="nav-item {{ Route::is('admin.stock-takes.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.stock-takes.index') }}">
                <i class="icon-clipboard menu-icon"></i>
                <span class="menu-title">Stock Taking</span>
            </a>
        </li>

        {{-- Settings --}}
        <li class="nav-item {{ Route::is('admin.settings') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.settings') }}">
                <i class="icon-cog menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>

    </ul>
</nav>
