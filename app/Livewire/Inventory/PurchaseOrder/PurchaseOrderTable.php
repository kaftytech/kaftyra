<?php

namespace App\Livewire\Inventory\PurchaseOrder;

use Livewire\Component;

use App\Models\PurchaseOrder;

class PurchaseOrderTable extends Component
{
    public function render()
    {
        $purchase_orders = PurchaseOrder::paginate(10);
        return view('livewire.inventory.purchase-order.purchase-order-table', compact('purchase_orders'));
    }
}
