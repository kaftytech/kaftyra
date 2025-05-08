<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Delivery;
use App\Models\User;
class DeliveryTable extends Component
{
    use WithPagination;

    public $statuses ;

    public function mount()
    {
        $this->statuses = Delivery::pluck('status', 'id')->toArray();
    }

    public function updatedStatuses($value, $key)
    {
        [$id] = explode('.', $key); // '123.status' → '123'
        Delivery::find($id)?->update(['status' => $value, 'delivered_date' => $value == 'delivered' ? now()->format('Y-m-d') : null,'updated_by' => auth()->id()]);

        return redirect()->route('shipping.index')->with('success', 'Delivery status updated successfully.');
    }


    public function render()
    {
        $deliveries = Delivery::paginate(10);
        $users = User::get();
        return view('livewire.orders.delivery-table', compact('deliveries','users'));
    }
}
