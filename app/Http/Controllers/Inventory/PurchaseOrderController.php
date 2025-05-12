<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('inventory.purchase-order.index');
    }

    public function create()
    {
        return view('inventory.purchase-order.create');
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        return view('inventory.purchase-order.edit', compact('purchaseOrder'));
    }

    public function convertBill($purchase_order_id)
    {
        return view('inventory.purchase-bill.create', compact('purchase_order_id'));
    }
}
