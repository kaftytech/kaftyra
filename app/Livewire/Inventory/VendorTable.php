<?php

namespace App\Livewire\Inventory;

use Livewire\Component;

use App\Models\Vendors;
class VendorTable extends Component
{
    public function render()
    {
        $vendors = Vendors::paginate(10);
        return view('livewire.inventory.vendor-table', compact('vendors'));
    }
}
