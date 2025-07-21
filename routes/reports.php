<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reports\ReportController;


Route::get('/stock-summary', [ReportController::class, 'stockSummary'])->name('reports.stock-summary');
Route::get('/stock-movement', [ReportController::class, 'stockMovement'])->name('reports.stock-movement');
Route::get('/sales-summary', [ReportController::class, 'salesSummary'])->name('reports.sales-summary');
Route::get('/top-selling-product', [ReportController::class, 'topSellingProduct'])->name('reports.top-selling-product');
Route::get('/customer-purchase-history', [ReportController::class, 'customerPurchaseHistory'])->name('reports.customer-purchase-history');
Route::get('/gstr-one-report', [ReportController::class, 'gstrOneReport'])->name('reports.gstr-one-report');
