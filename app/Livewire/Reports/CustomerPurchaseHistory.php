<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Customers;
use Livewire\WithPagination;
use App\Exports\CustomerPurchaseHistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerPurchaseHistory extends Component
{
    use WithPagination;

    public $customer_id;
    public $from_date;
    public $to_date;

    public function updated($property)
    {
        $this->resetPage();
    }
    public function export()
    {
        $filters = [
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'customer_id' => $this->customer_id,
        ];

        return Excel::download(new CustomerPurchaseHistoryExport($filters), 'customer_purchase_history.xlsx');
    }

    public function render()
    {
        $query = Invoice::with('customer')
            ->where('type', 'locked')
            ->where('status', '!=', 'cancelled')
            ->when($this->customer_id, fn($q) => $q->where('customer_id', $this->customer_id))
            ->when($this->from_date, fn($q) => $q->whereDate('invoice_date', '>=', $this->from_date))
            ->when($this->to_date, fn($q) => $q->whereDate('invoice_date', '<=', $this->to_date))
            ->latest();

        $invoices = $query->paginate(15);

        $customers = Customers::orderBy('customer_name')->get();

        return view('livewire.reports.customer-purchase-history', [
            'invoices' => $invoices,
            'customers' => $customers,
        ]);
    }
}
