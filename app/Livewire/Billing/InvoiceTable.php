<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Invoice;

class InvoiceTable extends Component
{
    public function render()
    {
        $invoices = Invoice::paginate(10);
        return view('livewire.billing.invoice-table', [
            'invoices' => $invoices,
        ]);
    }
}
