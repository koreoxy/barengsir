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

// Branch Selection
Route::middleware(['auth'])->group(function () {
    Route::get('/select-branch', [App\Http\Controllers\BranchSelectionController::class, 'index'])->name('branch.select');
    Route::post('/select-branch', [App\Http\Controllers\BranchSelectionController::class, 'setBranch'])->name('branch.set');
});

// Dashboard - Admin only
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin', 'branch'])
    ->name('dashboard');

// Home - Regular User
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// Product / Category / Brand routes (admin only)
Route::middleware(['auth', 'verified', 'role:admin', 'branch'])->group(function () {
    Route::resource('product', ProductController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('brand', BrandController::class);

    Route::get('/products/stock', [ProductController::class, 'stock'])->name('product.stock');
    Route::get('/products/stock-opname', [ProductController::class, 'stockOpname'])->name('product.stock_opname');
    Route::post('/products/stock-opname', [ProductController::class, 'storeStockOpname'])->name('product.stock_opname.store');
    Route::get('/products/barcode', [ProductController::class, 'barcode'])->name('product.barcode');

    // Transaksi / Kasir
    Route::get('/transaction', [App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transaction/checkout', [App\Http\Controllers\TransactionController::class, 'store'])->name('transaction.store');
    Route::get('/customer', function() { return 'Pelanggan'; })->name('customer.index');
    Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
    // Setting
    Route::get('/setting', [App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/store', [App\Http\Controllers\SettingController::class, 'updateStore'])->name('setting.store');
    Route::put('/setting/account', [App\Http\Controllers\SettingController::class, 'updateAccount'])->name('setting.account');

    // Vendor Management (Owner only)
    Route::middleware(['role:admin', 'branch'])->group(function () {
        Route::prefix('vendor')->name('vendor.')->group(function () {
            Route::resource('branches', BranchController::class);
            Route::resource('users', VendorUserController::class);
        });
    });
});

// Super Admin Auth Routes (terpisah, tidak di auth.php)
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\SuperAdminSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\SuperAdminSessionController::class, 'store']);
});

// Super Admin Authenticated Routes
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SuperAdminDashboardController::class, 'index'])
            ->name('dashboard');
        Route::post('/logout', [App\Http\Controllers\Auth\SuperAdminSessionController::class, 'destroy'])
            ->name('logout');
        
        Route::resource('vendors', App\Http\Controllers\SuperAdmin\VendorController::class);
    });

// Profile & Other common auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
