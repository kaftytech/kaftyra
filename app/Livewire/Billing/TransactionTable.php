<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Transaction;

class TransactionTable extends Component
{
    public $transactions;
    public $selectedTransaction;
    public $showDetails = false;

    public function mount()
    {
        $this->transactions = Transaction::latest()->get();
    }

    public function view($id)
    {
        $this->selectedTransaction = Transaction::findOrFail($id);
        $this->showDetails = true;
    }

    public function closeDetails()
    {
        $this->selectedTransaction = null;
        $this->showDetails = false;
    }
    public function render()
    {
        return view('livewire.billing.transaction-table');
    }
}
