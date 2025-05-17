<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stockSummary()
    {
        return view('reports.stock-summary');
    }

    public function stockMovement()
    {
        return view('reports.stock-movement-report');
    }

    public function salesSummary()
    {
        return view('reports.sales-summary-report');
    }

    public function topSellingProduct()
    {
        return view('reports.top-selling-products');
    }

    public function customerPurchaseHistory()
    {
        return view('reports.customer-purchase-history');
    }
}
