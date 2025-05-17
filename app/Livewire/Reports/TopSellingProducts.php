<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TopSellingProductsExport;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
class TopSellingProducts extends Component
{
    public $from_date;
    public $to_date;
    public $branch_id;
    public $products = [];
    public function export()
    {
        $filters = [
            'from_date' => $this->from_date,
            'to_date'   => $this->to_date,
            'branch_id' => $this->branch_id,
        ];

        return Excel::download(new TopSellingProductsExport($filters), 'top_selling_products.xlsx');
    }
    public function updated($property)
    {
        // Refresh data on any filter update
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $query = InvoiceItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(net_total) as total_amount')
            )
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->when($this->from_date, fn($q) => $q->whereDate('invoices.invoice_date', '>=', $this->from_date))
            ->when($this->to_date, fn($q) => $q->whereDate('invoices.invoice_date', '<=', $this->to_date))
            ->when($this->branch_id, fn($q) => $q->where('invoices.branch_id', $this->branch_id))
            ->where('invoices.type', 'locked')
            ->where('invoices.status', '!=', 'cancelled')
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();

        $this->products = $query;
    }

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->toDateString();
        $this->loadProducts();
    }
    public function render()
    {
        return view('livewire.reports.top-selling-products');
    }
}
