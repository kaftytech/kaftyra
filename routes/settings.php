<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::group(['prefix' => 'company'], function() {
    Route::get('/', [CompanyController::class, 'index'])->name('company.index');
    Route::get('/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('/', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('/{company}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('company.destroy');
});