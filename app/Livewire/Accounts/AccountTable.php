<?php

namespace App\Livewire\Accounts;

use Livewire\Component;
use App\Models\Account;
class AccountTable extends Component
{
    public function render()
    {
        $accounts = Account::all();
        return view('livewire.accounts.account-table', compact('accounts'));
    }
  
}
