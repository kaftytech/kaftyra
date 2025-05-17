<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Invoice;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesSummaryExport;

class SalesSummaryReport extends Component
{
    use WithPagination;

    public $from_date;
    public $to_date;
    public $product_id = '';
    public $branch_id = '';
    public $perPage = 10;

    public function updating($field)
    {
        $this->resetPage();
    }

    public function export()
    {
        $filters = [
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'product_id' => $this->product_id,
            'branch_id' => $this->branch_id,
        ];

        return Excel::download(new SalesSummaryExport($filters), 'sales_summary.xlsx');
    }

    public function render()
    {
        $query = InvoiceItem::select([
                'invoice_items.product_id',
                DB::raw('SUM(invoice_items.quantity) as total_qty'),
                DB::raw('SUM(invoice_items.total) as gross_amount'),
                DB::raw('SUM(invoice_items.discount_amount) as discount_amount'),
                DB::raw('SUM(invoice_items.tax_amount) as tax_amount'),
                DB::raw('SUM(invoice_items.net_total) as net_total'),
            ])
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->where('invoices.type', 'locked')
            ->whereNull('invoices.deleted_at')
            ->when($this->from_date, fn($q) => $q->whereDate('invoices.invoice_date', '>=', $this->from_date))
            ->when($this->to_date, fn($q) => $q->whereDate('invoices.invoice_date', '<=', $this->to_date))
            ->when($this->product_id, fn($q) => $q->where('invoice_items.product_id', $this->product_id))
            ->when($this->branch_id, fn($q) => $q->where('invoices.branch_id', $this->branch_id))
            ->groupBy('invoice_items.product_id');

        $sales = $query->with('product')->paginate($this->perPage);

        return view('livewire.reports.sales-summary-report', [
            'sales' => $sales,
            'branches' => Branch::all(),
            'products' => Product::all(),
        ]);
    }
}
