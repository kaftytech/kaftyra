<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\CRM\LeadsController;


Route::group(['prefix' => 'leads'], function() {
    Route::get('/', [LeadsController::class, 'index'])->name('leads.index');
    Route::get('/create', [LeadsController::class, 'create'])->name('leads.create');
    Route::post('/', [LeadsController::class, 'store'])->name('leads.store');
    Route::get('/{lead}', [LeadsController::class, 'show'])->name('leads.show');
    Route::get('/{lead}/edit', [LeadsController::class, 'edit'])->name('leads.edit');
    Route::put('/{lead}', [LeadsController::class, 'update'])->name('leads.update');
    Route::delete('/{lead}', [LeadsController::class, 'destroy'])->name('leads.destroy');
    Route::get('/leads/{lead}/convert/{type}', [LeadsController::class, 'convert'])->name('leads.convert');
});
Route::group(['prefix' => 'customers'], function() {
    Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/import', [CustomerController::class, 'import'])->name('customers.import');
    Route::get('/export', [CustomerController::class, 'export'])->name('customers.export');
});
