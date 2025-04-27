<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Billing\InvoiceController;

use App\Http\Controllers\Billing\PaymentController;

Route::group(['prefix' => 'invoices'], function() {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
});