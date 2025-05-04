<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Order\OrderRequestController;
use App\Http\Controllers\Order\ProductReturnsController;
use App\Http\Controllers\Order\ShippingController;

Route::group(['prefix' => 'requests'], function() {
    Route::get('/', [OrderRequestController::class, 'index'])->name('order-requests.index');
    Route::get('/create', [OrderRequestController::class, 'create'])->name('order-requests.create');
    Route::get('/edit/{id}', [OrderRequestController::class, 'edit'])->name('order-requests.edit');
    Route::get('/{id}', [OrderRequestController::class, 'show'])->name('order-requests.show');
    Route::post('/', [OrderRequestController::class, 'store'])->name('order-requests.store');
    Route::put('/{id}', [OrderRequestController::class, 'update'])->name('order-requests.update');
    Route::delete('/{id}', [OrderRequestController::class, 'destroy'])->name('order-requests.destroy');
});

Route::group(['prefix' => 'returns'], function() {
    Route::get('/', [ProductReturnsController::class, 'index'])->name('product-returns.index');
    Route::get('/create', [ProductReturnsController::class, 'create'])->name('product-returns.create');
    Route::get('/edit/{id}', [ProductReturnsController::class, 'edit'])->name('product-returns.edit');
    Route::get('/{id}', [ProductReturnsController::class, 'show'])->name('product-returns.show');
    Route::post('/', [ProductReturnsController::class, 'store'])->name('product-returns.store');
    Route::put('/{id}', [ProductReturnsController::class, 'update'])->name('product-returns.update');
    Route::delete('/{id}', [ProductReturnsController::class, 'destroy'])->name('product-returns.destroy');
});

Route::group(['prefix' => 'shipping'], function() {
    Route::get('/', [ShippingController::class, 'index'])->name('shipping.index');
    Route::post('/', [ShippingController::class, 'store'])->name('shipping.store');
});