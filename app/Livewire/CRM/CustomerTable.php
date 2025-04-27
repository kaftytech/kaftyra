<?php

namespace App\Livewire\CRM;

use Livewire\Component;
use App\Models\Customers;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;
    public function render()
    {
        $customers = Customers::query()
           
            ->paginate(10);
        return view('livewire.c-r-m.customer-table', [
            'customers' => $customers,
        ]);
    }
}
