<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\V1\Orders\OrderRequestController;
// use App\Http\Controllers\API\V1\Orders\ProductReturnController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\ProductController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route::post('order-requests', [OrderRequestController::class, 'store']);
// Route::post('product-returns', [ProductReturnController::class, 'store']);
// routes/api.php
Route::prefix('salesman')->group(function () {
    // Authentication
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    
    // Protected routes
    Route::middleware('auth:api')->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);
        
        // Assigned Orders
        Route::get('assigned-orders', [OrderController::class, 'assignedOrders']);
        Route::get('assigned-orders/{invoice}', [OrderController::class, 'showInvoice']);
        Route::post('assigned-orders/{invoice}/deliver', [OrderController::class, 'markAsDelivered']);
        Route::post('assigned-orders/{invoice}/payment', [OrderController::class, 'recordPayment']);
        
        // Orders (Requests)
        Route::get('orders', [OrderController::class, 'index']);
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders/{orderRequest}', [OrderController::class, 'show']);
        
        // Stock Tracker
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/search', [ProductController::class, 'search']);
    });
});