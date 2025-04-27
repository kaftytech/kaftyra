<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\VendorController;


Route::group(['prefix' => 'units'], function() {
    Route::get('/', [UnitController::class, 'index'])->name('units.index');
    Route::get('/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('/', [UnitController::class, 'store'])->name('units.store');
    Route::get('/{unit}', [UnitController::class, 'show'])->name('units.show');
    Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
});

Route::group(['prefix' => 'categories'], function() {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::group(['prefix' => 'products'], function() {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::post('/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/export', [ProductController::class, 'export'])->name('products.export');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::group(['prefix' => 'stock'], function() {
    Route::get('/', [StockController::class, 'index'])->name('stock.index');
    Route::get('/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/', [StockController::class, 'store'])->name('stock.store');
    Route::get('/{stock}', [StockController::class, 'show'])->name('stock.show');
    Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('stock.edit');
    Route::post('/{stock}', [StockController::class, 'update'])->name('stock.update');
    Route::delete('/{stock}', [StockController::class, 'destroy'])->name('stock.destroy');
});

Route::group(['prefix' => 'vendors'], function() {
    Route::get('/', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('/create', [VendorController::class, 'create'])->name('vendors.create');
    Route::post('/', [VendorController::class, 'store'])->name('vendors.store');
    Route::post('/import', [VendorController::class, 'import'])->name('vendors.import');
    Route::get('/export', [VendorController::class, 'export'])->name('vendors.export');
    Route::get('/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
    Route::get('/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::post('/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');
});
