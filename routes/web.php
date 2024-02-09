<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::get('/sales/show/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{id}/edit', [SaleController::class, 'edit'])->name('sales.edit');

});
