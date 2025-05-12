<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reports\ReportController;

    Route::get('/stock-summary', [ReportController::class, 'stockSummary'])->name('reports.stock-summary');
