<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\ProductReturn;
use App\Models\ProductReturnItems;

class ProductReturnsTable extends Component
{
    public function render()
    {
        $returns = ProductReturn::orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.orders.product-returns-table', compact('returns'));
    }
}
