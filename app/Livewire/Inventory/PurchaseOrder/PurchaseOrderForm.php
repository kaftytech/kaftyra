<?php

namespace App\Livewire\Inventory\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendors;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderForm extends Component
{
    public $purchaseOrderId;
    public $vendor_id;
    public $po_number;
    public $po_date;
    public $status = 'pending';
    public $notes;

    public $purchaseOrderItems = [];
    public $vendors = [];
    public $availableProducts = [];

    public function mount($purchaseOrderId = null)
    {
        if ($purchaseOrderId) {
            $purchaseOrder = PurchaseOrder::with('orderItems.product')->findOrFail($purchaseOrderId);
            $this->fill($purchaseOrder->toArray());

             $this->purchaseOrderItems = $purchaseOrder->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'order_request_id' => $item->order_request_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'notes' => $item->notes,
                ];
            })->toArray();
        } else {
            $this->po_date = today()->format('Y-m-d');
            $this->po_number = 'PO-' . time();
            $this->addItem();
        }
        $this->vendors = Vendors::all();
        $this->availableProducts = Product::with('unit')->get();

    }

    public function addItem()
    {
        $this->purchaseOrderItems[] = [
            'product_id' => '',
            'product_name' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
            'discount' => 0,
            'tax' => 0,
            'net_total' => 0,
            'available_stock' => 0,
            // 'hsn_code' => '',
            'tax_percentage' => 0,
        ];
    }

    public function render()
    {
        return view('livewire.inventory.purchase-order.purchase-order-form');
    }
}
