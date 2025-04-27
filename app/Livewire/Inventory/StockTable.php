<?php

namespace App\Livewire\Inventory;

use Livewire\Component;

use App\Models\Stock;
class StockTable extends Component
{
    public function render()
    {
        $stocks = Stock::paginate(10);

        return view('livewire.inventory.stock-table', compact('stocks'));
    }
}
