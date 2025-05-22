<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
class CreditNoteController extends Controller
{
    public function createCreditNote($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('billing.credit-notes.create')->with('invoice', $invoice);
    }

    public function creditNoteIndex()
    {
        return view('billing.credit-notes.index');
    }
}
