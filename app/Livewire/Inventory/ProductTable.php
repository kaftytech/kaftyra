<?php

namespace App\Livewire\Inventory;

use Livewire\Component;

use App\Models\Product;


class ProductTable extends Component
{
    public function render()
    {
        $products = Product::paginate(10);
        return view('livewire.inventory.product-table', compact('products'));
    }
}
