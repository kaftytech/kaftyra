<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('billing.payments.index');
    }

    public function transactionIndex()
    {
        return view('billing.payments.transaction-index');
    }
}
