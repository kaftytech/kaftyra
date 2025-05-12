<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Traits\HandlesVendorSelection;
use App\Traits\HandlesPurchaseOrderSelection;
use App\Models\PurchaseOrder;
use App\Models\PurchaseBill;
use DB;
class BillingForm extends Component
{
    use HandlesVendorSelection, HandlesPurchaseOrderSelection;

    public $bill_date, $notes, $purchase_order_id, $selectedVendor;
    public $bill_id;
    public $purchaseOrderItems = [];
    public $purchaseOrder;
    public $showOrderSelection = false;


    public function mount($bill_id = null,$purchase_order_id = null) 
    {
        if($bill_id) {
            $this->bill_id = $bill_id;
            $purchaseBillOrder = PurchaseBill::with('purchaseOrder.orderItems.product')->find($bill_id);
            // dd($this->purchaseBillOrder->purchaseOrder);
            $this->bill_date = $purchaseBillOrder->bill_date;
            $this->notes = $purchaseBillOrder->notes;
            $this->order_search_by_query = $purchaseBillOrder->purchaseOrder->po_number;
            $this->order_search_by_query_status = 'selected';

            $this->search_by_query = $purchaseBillOrder->purchaseOrder->vendor->company_name . ' | ' . $purchaseBillOrder->purchaseOrder->vendor->phone;
            $this->search_by_query_status = 'selected';
            $this->purchaseOrderItems = $purchaseBillOrder->purchaseOrder->orderItems;
            $this->showOrderSelection = true;
            $this->updatePurchaseOrderId($purchaseBillOrder->purchaseOrder->id);

        }
        elseif($purchase_order_id) {
            $this->purchaseOrder = PurchaseOrder::with('orderItems.product')->find($purchase_order_id);
            $this->purchaseOrderItems = $this->purchaseOrder->orderItems;
            $this->order_search_by_query = $this->purchaseOrder->po_number;
            $this->order_search_by_query_status = 'selected';

            $this->search_by_query = $this->purchaseOrder->vendor->company_name . ' | ' . $this->purchaseOrder->vendor->phone;
            $this->search_by_query_status = 'selected';
            $this->showOrderSelection = true;
        }
        {
            $this->bill_date = today()->format('Y-m-d');
        }
    }

    public function updateSelectedVendor($id)
    {
        $this->orders = PurchaseOrder::with('orderItems.product')->where('vendor_id', $id)->get();
    }
    public function updatePurchaseOrderId($id)
    {
        $this->purchaseOrder = PurchaseOrder::with('orderItems.product')->find($id);
        $this->purchaseOrderItems = $this->purchaseOrder->orderItems;
        $this->showOrderSelection = true;
    }

    public function submitOrder()
    {
          DB::beginTransaction();
          try {
              $bill = PurchaseBill::updateOrCreate(
                ['id' => $this->bill_id ?? null],
                [
                  'purchase_order_id' => $this->purchaseOrder->id,
                  'total_amount' => $this->purchaseOrder->total_amount,
                  'due_amount' => $this->purchaseOrder->total_amount,
                  'bill_date' => $this->bill_date,
                  'notes' => $this->notes
              ]);

              PurchaseOrder::find($this->purchaseOrder->id)->update(['bill_status' => 'billed']);

            DB::commit();
             return redirect()->route('purchase-bills.index')->with('success', 'Purchase Bill created successfully.');
         } catch (\Exception $e) {
             DB::rollBack();
             throw $e;
         }
    }

    public function render()
    {
        return view('livewire.inventory.billing-form');
    }
}
