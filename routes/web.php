<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    #return view('welcome');
    return redirect('admin/login');
});

Route::get('/test-routes', function () {
    return [
        'categories_index' => route('admin.categories.index'),
        'products_index' => route('admin.products.index'),
        'orders_index' => route('admin.orders.index'),
        'dashboard' => route('admin.dashboard'),
    ];
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});



require __DIR__.'/auth.php';
// Include admin routes
require __DIR__.'/admin.php';