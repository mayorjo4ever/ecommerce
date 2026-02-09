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
    
    // Guest routes (Login) - No middleware
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');

    // Authenticated routes - Admin middleware
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
        
        // Admin Management
        Route::resource('admins', AdminController::class);
        
        // Role Management
        Route::resource('roles', RoleController::class);
        
        // Permission Management
        Route::resource('permissions', PermissionController::class)->except(['show']);
        
        // Point of Sale (POS)
        Route::prefix('pos')->name('pos.')->group(function () {
            Route::get('/', [POSController::class, 'index'])->name('index');
            Route::get('/search-products', [POSController::class, 'searchProducts'])->name('search.products');
            Route::get('/product/{id}', [POSController::class, 'getProduct'])->name('get.product');
            Route::post('/search-qr', [POSController::class, 'searchByQRCode'])->name('search.qr');
            Route::get('/search-customer', [POSController::class, 'searchCustomer'])->name('search.customer');
            Route::post('/create-customer', [POSController::class, 'createQuickCustomer'])->name('create.customer');
            Route::post('/process-sale', [POSController::class, 'processSale'])->name('process.sale');
            Route::get('/receipt/{order}', [POSController::class, 'printReceipt'])->name('receipt');
            Route::get('/history', [POSController::class, 'salesHistory'])->name('history');
        });
    });
});