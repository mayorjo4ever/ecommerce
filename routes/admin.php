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
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockTakeController;
use Illuminate\Support\Facades\Route;


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

        // ═══════════════════════════════════════════════════════════════
        // 1.  ADD THIS ROUTE to admin.php  (inside the middleware group)
        // ═══════════════════════════════════════════════════════════════

        // Barcode duplicate-check (called by the JS in the product form)
        Route::get('products/check-barcode', [ProductController::class, 'checkBarcode'])
            ->name('products.check-barcode');

        // NOTE: Place this BEFORE Route::resource('products', ...) so Laravel
        // doesn't mistake "check-barcode" for a {product} wildcard.

        
        // Products
        Route::resource('products', ProductController::class);
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Orders
        Route::resource('orders', OrderController::class);       
        Route::resource('orders', OrderController::class);
        Route::get('orders-export', [OrderController::class, 'export'])->name('orders.export');

        
        
        // Customers
        Route::resource('customers', CustomerController::class);
        
        // Coupons        
        Route::resource('coupons', CouponController::class);
        Route::get('coupons-generate', [CouponController::class, 'generate'])->name('coupons.generate');
        Route::post('coupons-validate', [CouponController::class, 'validate'])->name('coupons.validate');
        

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
            Route::post('/record-payment/{order}', [POSController::class, 'recordPayment'])->name('record.payment');
        });
        
        // POS History Export
        Route::get('pos/export', [POSController::class, 'exportHistory'])->name('pos.export');
        
        

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Stock Takes
    Route::resource('stock-takes', StockTakeController::class);
    Route::post('stock-takes/{stockTake}/complete', [StockTakeController::class, 'complete'])->name('stock-takes.complete');
    Route::post('stock-takes/{stockTake}/items/{item}', [StockTakeController::class, 'updateItem'])->name('stock-takes.update-item');
    Route::get('stock-takes/{stockTake}/export', [StockTakeController::class, 'export'])->name('stock-takes.export');

    // Stock Movements
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/report',            [StockController::class, 'report'])->name('report');
        Route::get('/history/{product}', [StockController::class, 'history'])->name('history');
        Route::get('/create/{product}',  [StockController::class, 'create'])->name('create');
        Route::post('/store/{product}',  [StockController::class, 'store'])->name('store');
    });

    }); // end middleware('admin')

});