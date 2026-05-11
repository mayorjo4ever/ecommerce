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
use App\Http\Controllers\Admin\ProductImageController;   // ← NEW
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockTakeController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->group(function () {

    // ── Guest (login) ─────────────────────────────────────────
    Route::get ('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');

    // ── Authenticated ─────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get ('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('profile',  fn() => view('admin.profile') )->name('profile');
        Route::get('settings', fn() => view('admin.settings'))->name('settings');

        // ── Products ─────────────────────────────────────────
        //
        // IMPORTANT: the two named sub-routes MUST come before
        // Route::resource() so Laravel doesn't swallow them as
        // {product} wildcards.
        //
        // 1. Barcode duplicate-check (used by create & edit JS)
        Route::get('products/check-barcode', [ProductController::class, 'checkBarcode'])
             ->name('products.check-barcode');

        // 2. AJAX delete a single gallery image (used by edit page)
        Route::delete('products/images/{image}', [ProductImageController::class, 'destroy'])
             ->name('products.images.destroy');

        // 3. Full CRUD resource
        Route::resource('products', ProductController::class);

        // ── Categories ───────────────────────────────────────
        Route::resource('categories', CategoryController::class);

        // ── Orders ───────────────────────────────────────────
        Route::resource('orders', OrderController::class);           // removed duplicate
        Route::get('orders-export', [OrderController::class, 'export'])->name('orders.export');

        // ── Customers ────────────────────────────────────────
        Route::resource('customers', CustomerController::class);

        // ── Coupons ──────────────────────────────────────────
        Route::resource('coupons', CouponController::class);
        Route::get ('coupons-generate', [CouponController::class, 'generate'])->name('coupons.generate');
        Route::post('coupons-validate', [CouponController::class, 'validate'])->name('coupons.validate');

        // ── Reviews ──────────────────────────────────────────
        Route::resource('reviews', ReviewController::class);

        // ── Admin management ─────────────────────────────────
        Route::resource('admins', AdminController::class);

        // ── Roles & Permissions ──────────────────────────────
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class)->except(['show']);

        // ── POS ───────────────────────────────────────────────
        Route::prefix('pos')->name('pos.')->group(function () {
            Route::get ('/',                [POSController::class, 'index'])->name('index');
            Route::get ('/search-products', [POSController::class, 'searchProducts'])->name('search.products');
            Route::get ('/product/{id}',    [POSController::class, 'getProduct'])->name('get.product');
            Route::post('/search-qr',       [POSController::class, 'searchByQRCode'])->name('search.qr');
            Route::get ('/search-customer', [POSController::class, 'searchCustomer'])->name('search.customer');
            Route::post('/create-customer', [POSController::class, 'createQuickCustomer'])->name('create.customer');
            Route::post('/process-sale',    [POSController::class, 'processSale'])->name('process.sale');
            Route::get ('/receipt/{order}', [POSController::class, 'printReceipt'])->name('receipt');
            Route::get ('/history',         [POSController::class, 'salesHistory'])->name('history');
            Route::post('/record-payment/{order}', [POSController::class, 'recordPayment'])->name('record.payment');
        });
        Route::get('pos/export', [POSController::class, 'exportHistory'])->name('pos.export');

        // ── Reports ───────────────────────────────────────────
        Route::get('reports',        [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        // ── Stock Takes ───────────────────────────────────────
        Route::resource('stock-takes', StockTakeController::class);
        Route::post('stock-takes/{stockTake}/complete',        [StockTakeController::class, 'complete'])->name('stock-takes.complete');
        Route::post('stock-takes/{stockTake}/items/{item}',    [StockTakeController::class, 'updateItem'])->name('stock-takes.update-item');
        Route::get ('stock-takes/{stockTake}/export',          [StockTakeController::class, 'export'])->name('stock-takes.export');

        // ── Stock Movements ───────────────────────────────────
        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get ('/report',           [StockController::class, 'report'])->name('report');
            Route::get ('/history/{product}',[StockController::class, 'history'])->name('history');
            Route::get ('/create/{product}', [StockController::class, 'create'])->name('create');
            Route::post('/store/{product}',  [StockController::class, 'store'])->name('store');
        });

    }); // end middleware('admin')

});