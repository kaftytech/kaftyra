<?php

namespace App\Livewire\Accounts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AccountTransactions;
class AccountTransactionTable extends Component
{
    use WithPagination;

    // public $accountId;

    // public function mount($accountId)
    // {
    //     $this->accountId = $accountId;
    // }

    public function render()
    {
        $transactions = AccountTransactions::latest()
            ->paginate(10);

        return view('livewire.accounts.account-transaction-table', [
            'transactions' => $transactions,
        ]);
    }
}
