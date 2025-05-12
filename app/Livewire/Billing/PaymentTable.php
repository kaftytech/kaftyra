<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Payment;
class PaymentTable extends Component
{
    public $payments;
    public $selectedPayment;
    public $showDetails = false;

    public function mount()
    {
        $this->payments = Payment::latest()->get();
    }

    public function view($id)
    {
        $this->selectedPayment = Payment::findOrFail($id);
        $this->showDetails = true;
    }

    public function closeDetails()
    {
        $this->selectedPayment = null;
        $this->showDetails = false;
    }
    public function render()
    {
        return view('livewire.billing.payment-table');
    }
}
