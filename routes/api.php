<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Orders\OrderRequestController;
use App\Http\Controllers\API\V1\Orders\ProductReturnController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('order-requests', [OrderRequestController::class, 'store']);
Route::post('product-returns', [ProductReturnController::class, 'store']);
