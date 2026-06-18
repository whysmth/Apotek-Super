<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('medicines', MedicineController::class);

Route::get('sales/export', [SaleController::class, 'export'])->name('sales.export');
Route::get('sales/{id}/json', [SaleController::class, 'json'])->name('sales.json');
Route::post('sales/{id}/void', [SaleController::class, 'void'])->name('sales.void');
Route::resource('sales', SaleController::class);

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

Route::post('suppliers/ajax', [SupplierController::class, 'storeAjax'])->name('suppliers.storeAjax');
Route::post('categories/ajax', [CategoryController::class, 'storeAjax'])->name('categories.storeAjax');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';