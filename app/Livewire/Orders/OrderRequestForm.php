<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\OrderRequest;
use App\Models\OrderItem;
use App\Models\Customers;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderRequestForm extends Component
{
    public $orderRequestId;
    public $customer_id;
    public $order_id;
    public $request_date;
    public $status = 'pending';
    public $notes;

    public $requestItems = [];
    public $customers = [];
    public $availableProducts = [];

    public function mount($orderRequestId = null)
    {
        $this->request_date = today()->format('Y-m-d');

        if ($orderRequestId) {
            $orderRequest = OrderRequest::with('orderItems.product')->findOrFail($orderRequestId);
            $this->fill($orderRequest->toArray());

             $this->requestItems = $orderRequest->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'order_request_id' => $item->order_request_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'notes' => $item->notes,
                ];
            })->toArray();
        } else {
            $this->addRequestItem();
        }
        $this->customers = Customers::all();
        $this->availableProducts = Product::with('unit')->get();

    }

    public function addRequestItem()
    {
        $this->requestItems[] = [
            'product_id' => '',
            'quantity' => 1,
            'notes' => '',
        ];
    }
    
    public function submitRequest()
    {
        // dd($this->requestItems, $this->customer_id, $this->order_id, $this->request_date, $this->notes);
        $orderRequest = OrderRequest::updateOrCreate(
            ['id' => $this->orderRequestId],
            [
             'customer_id' => $this->customer_id ?? null,
             'request_date' => $this->request_date,
             'notes' => $this->notes ?? null,
            ]
        );

        foreach ($this->requestItems as $item) {
            OrderItem::updateOrCreate(
                ['id' => $item['id'] ?? null], // Update if id exists
                [
                    'order_request_id' => $orderRequest->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ]
            );
        }

        session()->flash('success', 'Order Request saved successfully.');

        return redirect()->route('order-requests.index');
    }
    public function render()
    {
        return view('livewire.orders.order-request-form');
    }
}
