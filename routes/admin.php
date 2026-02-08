<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest routes (Login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
        // Profile & Settings
        Route::get('profile', function () { 
            return view('admin.profile'); 
        })->name('profile');
        
        Route::get('settings', function () { 
            return view('admin.settings'); 
        })->name('settings');
        
        // Products
        Route::resource('products', ProductController::class);
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Orders
        Route::resource('orders', OrderController::class);
        
        // Customers
        Route::resource('customers', CustomerController::class);
        
        // Coupons
        Route::resource('coupons', CouponController::class);
        
        // Reviews
        Route::resource('reviews', ReviewController::class);
        
        // Admin Management - accessible to all admins for now
        Route::resource('admins', AdminController::class);
        
        // Role Management - accessible to all admins for now
        Route::resource('roles', RoleController::class);
        
        // Permission Management
        // Permission Management
        Route::resource('permissions', PermissionController::class)->except(['show']);
        #Route::get('permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
        #Route::post('permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'store'])->name('permissions.store');
        #Route::delete('permissions/{permission}', [\App\Http\Controllers\Admin\PermissionController::class, 'destroy'])->name('permissions.destroy');

    });
});


// Point of Sale (POS) - with permission check
Route::prefix('pos')->name('pos.')->middleware('permission:access pos,admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\POSController::class, 'index'])->name('index');
    Route::get('/search-products', [\App\Http\Controllers\Admin\POSController::class, 'searchProducts'])->name('search.products');
    Route::get('/product/{id}', [\App\Http\Controllers\Admin\POSController::class, 'getProduct'])->name('get.product');
    Route::get('/search-customer', [\App\Http\Controllers\Admin\POSController::class, 'searchCustomer'])->name('search.customer');
    Route::post('/create-customer', [\App\Http\Controllers\Admin\POSController::class, 'createQuickCustomer'])->name('create.customer');
    Route::post('/process-sale', [\App\Http\Controllers\Admin\POSController::class, 'processSale'])->name('process.sale');
    Route::get('/receipt/{order}', [\App\Http\Controllers\Admin\POSController::class, 'printReceipt'])->name('receipt');
    Route::get('/history', [\App\Http\Controllers\Admin\POSController::class, 'salesHistory'])->name('history');
});


/**

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest routes (Login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
        // Profile & Settings
        Route::get('profile', function () { 
            return view('admin.profile'); 
        })->name('profile');
        
        Route::get('settings', function () { 
            return view('admin.settings'); 
        })->name('settings');
        
        // Products
        Route::resource('products', ProductController::class);
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Orders
        Route::resource('orders', OrderController::class);
        
        // Customers
        Route::resource('customers', CustomerController::class);
        
        // Coupons
        Route::resource('coupons', CouponController::class);
        
        // Reviews
        Route::resource('reviews', ReviewController::class);
        
        // Admin Management (only for users with permission)
        Route::middleware('permission:view admins,admin')->group(function () {
            Route::resource('admins', AdminController::class);
        });
        
        // Role Management (only for users with permission)
        Route::middleware('permission:view roles,admin')->group(function () {
            Route::resource('roles', RoleController::class);
        });
    });
}); 
 * 
 */