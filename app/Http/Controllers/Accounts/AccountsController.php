<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
class AccountsController extends Controller
{
    public function index(){
        return view('accounts.index');
    }

    public function transactionIndex(){

        return view('accounts.transactions.index');
    }
}
