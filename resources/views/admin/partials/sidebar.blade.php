<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pos-menu" aria-expanded="false" aria-controls="pos-menu">
                <i class="icon-basket menu-icon"></i>
                <span class="menu-title">Point of Sale</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="pos-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.pos.index') }}">New Sale</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.pos.history') }}">Sales History</a></li>
                </ul>
            </div>
        </li>
        
        
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#products-menu" aria-expanded="false" aria-controls="products-menu">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="products-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{  route('admin.products.index')  }}">All Products</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{  route('admin.products.create')  }}">Add Product</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#categories-menu" aria-expanded="false" aria-controls="categories-menu">
                <i class="icon-columns menu-icon"></i>
                <span class="menu-title">Categories</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="categories-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.categories.index') }}">All Categories</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.categories.create') }}">Add Category</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.orders.index') }}">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Orders</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.customers.index') }}">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Customers</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                <i class="icon-tag menu-icon"></i>
                <span class="menu-title">Coupons</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                <i class="icon-star menu-icon"></i>
                <span class="menu-title">Reviews</span>
            </a>
        </li>

       @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->canManageAdmins())
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#admins-menu" aria-expanded="false" aria-controls="admins-menu">
                <i class="icon-lock menu-icon"></i>
                <span class="menu-title">Admin Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="admins-menu">
                  <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('admin.admins.index') }}">All Admins</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('admin.roles.index') }}">Roles</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                    </ul>
            </div>
        </li>
        @endif
         

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings') }}">
                <i class="icon-cog menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>
    </ul>
</nav>