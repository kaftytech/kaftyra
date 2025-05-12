<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseBill;

class PurchaseBillController extends Controller
{
    public function index()
    {
        return view('inventory.purchase-bill.index');
    }

    public function create()
    {
        return view('inventory.purchase-bill.create');
    }

    public function edit($id)
    {
        $purchaseBill = PurchaseBill::findOrFail($id);
        return view('inventory.purchase-bill.edit', compact('purchaseBill'));
    }
    
}
