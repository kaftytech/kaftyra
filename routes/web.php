<?php

use Illuminate\Support\Facades\Route;

Route::prefix('crm')->group(base_path('routes/crm.php'));
Route::prefix('inventory')->group(base_path('routes/inventory.php'));
Route::prefix('billing')->group(base_path('routes/billing.php'));
Route::prefix('settings')->group(base_path('routes/settings.php'));
Route::prefix('orders')->group(base_path('routes/orders.php'));

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
