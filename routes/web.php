<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

// Root - redirect to login if not auth
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard - Admin only
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('dashboard');

// Home - Regular User
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// Product / Category / Brand routes (admin only)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('product', ProductController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('brand', BrandController::class);

    Route::get('/products/stock', [ProductController::class, 'stock'])->name('product.stock');
    Route::get('/products/stock-opname', [ProductController::class, 'stockOpname'])->name('product.stock_opname');
    Route::post('/products/stock-opname', [ProductController::class, 'storeStockOpname'])->name('product.stock_opname.store');
    Route::get('/products/barcode', [ProductController::class, 'barcode'])->name('product.barcode');

    // Placeholders for other menus
    Route::get('/transaction', function() { return 'Transaksi'; })->name('transaction.index');
    Route::get('/customer', function() { return 'Pelanggan'; })->name('customer.index');
    Route::get('/report', function() { return 'Laporan'; })->name('report.index');
    Route::get('/setting', function() { return 'Pengaturan'; })->name('setting.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
