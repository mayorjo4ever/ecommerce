<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- Dashboard --}}
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- Point of Sale --}}
        <li class="nav-item {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
            <a class="nav-link"
               data-toggle="collapse"
               href="#pos-menu"
               aria-expanded="{{ request()->routeIs('admin.pos.*') ? 'true' : 'false' }}"
               aria-controls="pos-menu">

                <i class="icon-basket menu-icon"></i>
                <span class="menu-title">Point of Sale</span>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse {{ request()->routeIs('admin.pos.*') ? 'show' : '' }}"
                 id="pos-menu">

                <ul class="nav flex-column sub-menu">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pos.index') ? 'active' : '' }}"
                           href="{{ route('admin.pos.index') }}">
                            New Sale
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pos.history') ? 'active' : '' }}"
                           href="{{ route('admin.pos.history') }}">
                            Sales History
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        {{-- Products --}}
        <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <a class="nav-link"
               data-toggle="collapse"
               href="#products-menu"
               aria-expanded="{{ request()->routeIs('admin.products.*') ? 'true' : 'false' }}"
               aria-controls="products-menu">

                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse {{ request()->routeIs('admin.products.*') ? 'show' : '' }}"
                 id="products-menu">

                <ul class="nav flex-column sub-menu">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}"
                           href="{{ route('admin.products.index') }}">
                            All Products
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}"
                           href="{{ route('admin.products.create') }}">
                            Add Product
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        {{-- Categories --}}
        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link"
               data-toggle="collapse"
               href="#categories-menu"
               aria-expanded="{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}"
               aria-controls="categories-menu">

                <i class="icon-columns menu-icon"></i>
                <span class="menu-title">Categories</span>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}"
                 id="categories-menu">

                <ul class="nav flex-column sub-menu">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
                           href="{{ route('admin.categories.index') }}">
                            All Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}"
                           href="{{ route('admin.categories.create') }}">
                            Add Category
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        {{-- Orders --}}
        <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               href="{{ route('admin.orders.index') }}">

                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Orders</span>
            </a>
        </li>

        {{-- Customers --}}
        <li class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
               href="{{ route('admin.customers.index') }}">

                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Customers</span>
            </a>
        </li>

        {{-- Coupons --}}
        @cannot('view coupons')
        <li class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
               href="{{ route('admin.coupons.index') }}">

                <i class="icon-tag menu-icon"></i>
                <span class="menu-title">Coupons</span>
            </a>
        </li> @endcan

        {{-- Reviews --}}
         @cannot('view reviews')
        <li class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
               href="{{ route('admin.reviews.index') }}">

                <i class="icon-star menu-icon"></i>
                <span class="menu-title">Reviews</span>
            </a>
        </li>  @endcan

        {{-- Admin Management --}}
        @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->canManageAdmins())

            @php
                $adminManagementActive =
                    request()->routeIs('admin.admins.*') ||
                    request()->routeIs('admin.roles.*') ||
                    request()->routeIs('admin.permissions.*');
            @endphp

            <li class="nav-item {{ $adminManagementActive ? 'active' : '' }}">

                <a class="nav-link"
                   data-toggle="collapse"
                   href="#admins-menu"
                   aria-expanded="{{ $adminManagementActive ? 'true' : 'false' }}"
                   aria-controls="admins-menu">

                    <i class="icon-lock menu-icon"></i>
                    <span class="menu-title">Admin Management</span>
                    <i class="menu-arrow"></i>
                </a>

                <div class="collapse {{ $adminManagementActive ? 'show' : '' }}"
                     id="admins-menu">

                    <ul class="nav flex-column sub-menu">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"
                               href="{{ route('admin.admins.index') }}">
                                All Admins
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                               href="{{ route('admin.roles.index') }}">
                                Roles
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                               href="{{ route('admin.permissions.index') }}">
                                Permissions
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

        @endif

        {{-- Reports --}}
        <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
               href="{{ route('admin.reports.index') }}">

                <i class="icon-chart menu-icon"></i>
                <span class="menu-title">Reports</span>
            </a>
        </li>

        {{-- Stock Taking --}}
        <li class="nav-item {{ request()->routeIs('admin.stock-takes.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.stock-takes.*') ? 'active' : '' }}"
               href="{{ route('admin.stock-takes.index') }}">

                <i class="icon-clipboard menu-icon"></i>
                <span class="menu-title">Stock Taking</span>
            </a>
        </li>

        {{-- Settings --}}
         @cannot('view settings')
        <li class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
               href="{{ route('admin.settings') }}">

                <i class="icon-cog menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>@endcan

    </ul>
</nav>