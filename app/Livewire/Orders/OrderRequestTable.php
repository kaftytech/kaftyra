<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderRequest;
class OrderRequestTable extends Component
{
    public function render()
    {
        $orders = OrderRequest::orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.orders.order-request-table', compact('orders'));
    }
}
