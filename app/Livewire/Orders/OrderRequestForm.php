<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\OrderRequest;
use App\Models\OrderItem;
use App\Models\Customers;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesProductSelection;
use App\Traits\HandlesCustomerSelection;

class OrderRequestForm extends Component
{
    use HandlesProductSelection, HandlesCustomerSelection;

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
            $this->search_by_query = $orderRequest->customer->customer_name . ' | ' . $orderRequest->customer->phone;
            $this->customer = $orderRequest->customer;
            $this->search_by_query_status = 'selected';

            $this->fill($orderRequest->toArray());

             $this->requestItems = $orderRequest->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'order_request_id' => $item->order_request_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'notes' => $item->notes,
                    'product_search' => $item->product->name . ' (' . $item->product->product_code . ')',
                    'search_status' => 'selected',
                ];
            })->toArray();
        } else {
            $this->addRequestItem();
        }
        $this->customers = Customers::all();
        $this->availableProducts = Product::with('unit')->get();

    }
    public function removeRequestItem($index)
    {
        if (count($this->requestItems) > 1) {
            unset($this->requestItems[$index]);
            $this->requestItems = array_values($this->requestItems);
        }
    }
    public function updatedRequestItems($value, $index)
    {
        $parts = explode('.', $index);    
        // Handle product search
        if (count($parts) == 2 && $parts[1] === 'product_search') {
            $this->handleProductSearchWrapper($parts[0], $value);
        }
    }
    public function handleProductSearchWrapper($index, $query)
    {
        $this->handleProductSearch($this->requestItems, $index, $query);
    }

    public function selectProductWrapper($index, $productId)
    {
        $error = $this->selectProduct(
            $this->requestItems,
            $index,
            $productId
        );

        if ($error) {
            $this->addError("requestItems.$index.product_id", $error);
        }
    }


    public function highlightProductNextWrapper($index)
    {
        $this->highlightProductNext($this->requestItems, $index);
    }

    public function highlightProductPreviousWrapper($index)
    {
        $this->highlightProductPrevious($this->requestItems, $index);
    }

    public function selectHighlightedProductWrapper($index)
    {
        dd($this->requestItems);

        $this->selectHighlightedProduct($this->requestItems, $index, fn($i, $id) => $this->selectProductWrapper($i, $id));
    }

    public function addRequestItem()
    {
        $this->requestItems[] = [
            'product_id' => '',
            'quantity' => 1,
            'notes' => '',
            'product_search' => '',
            'search_results' => [],
            'search_status' => '',
            'highlight_index' => 0,
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
