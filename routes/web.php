<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;

Route::prefix('crm')->group(base_path('routes/crm.php'));
Route::prefix('inventory')->group(base_path('routes/inventory.php'));
Route::prefix('billing')->group(base_path('routes/billing.php'));
Route::prefix('settings')->group(base_path('routes/settings.php'));
Route::prefix('orders')->group(base_path('routes/orders.php'));
Route::prefix('admin')->group(base_path('routes/admin.php'));

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

Route::post('/store', [TestController::class, 'store'])->name('purchase-orders.store');
Route::get('/dashboard/category-sales', [DashboardController::class, 'getCategorySales']);
Route::get('/dashboard/revenue-data', [DashboardController::class, 'getRevenueData']);
Route::get('/dashboard/recent-activities', [DashboardController::class, 'getRecentActivities']);
Route::get('/stats', [DashboardController::class, 'getStats']);

Route::get('business-units/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('finance/expenses', [ExpenseController::class, 'index'])->name('expenses.index');

Route::get('/employee/profile', [EmployeeController::class, 'index'])->name('employee.index');

Route::get('/branch', [BranchController::class, 'index'])->name('branch.index');


Route::get('/notifications/{id}/read', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return redirect()->back();
})->name('notifications.read');
Route::get('/notifications', function () {
    return view('notifications.index');
})->name('notifications.index');
