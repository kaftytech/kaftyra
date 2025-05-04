<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Delivery;
use App\Models\User;
class DeliveryTable extends Component
{

    public function render()
    {
        $deliveries = Delivery::paginate(10);
        $users = User::get();
        return view('livewire.orders.delivery-table', compact('deliveries','users'));
    }
}
