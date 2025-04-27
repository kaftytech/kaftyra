<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\InvoiceItem;
class InvoiceController extends Controller
{
    public function index()
    {
        return view('billing.invoice.index');
    }

    public function create()
    {
        return view('billing.invoice.create');
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('billing.invoice.edit', compact('invoice'));
    }
}
