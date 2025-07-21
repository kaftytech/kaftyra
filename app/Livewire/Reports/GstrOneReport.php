<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Branch;
use App\Exports\GstrOneExport;
use Maatwebsite\Excel\Facades\Excel;

class GstrOneReport extends Component
{
    use WithPagination;

    public $from_date;
    public $to_date;
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
            'branch_id' => $this->branch_id,
        ];

        return Excel::download(new GstrOneExport($filters), 'gstr1_report.xlsx');
    }

    public function render()
    {
        $query = DB::table('invoices')
    ->select(
        'invoices.id',
        'invoices.invoice_number',
        'invoices.invoice_date',
        'customers.customer_name',
        'customers.gst_number',
        DB::raw('SUM(invoice_items.quantity * invoice_items.price) as taxable_value'),
        DB::raw('SUM(CASE WHEN taxables.tax_name = "CGST" THEN taxables.amount ELSE 0 END) as cgst'),
        DB::raw('SUM(CASE WHEN taxables.tax_name = "SGST" THEN taxables.amount ELSE 0 END) as sgst'),
        DB::raw('SUM(CASE WHEN taxables.tax_name = "IGST" THEN taxables.amount ELSE 0 END) as igst'),
        DB::raw('SUM(invoice_items.quantity * invoice_items.price) + SUM(taxables.amount) as total_invoice_value')
    )
    ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
    ->leftJoin('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
    ->leftJoin('taxables', function ($join) {
        $join->on('invoices.id', '=', 'taxables.taxable_id')
             ->where('taxables.taxable_type', '=', \App\Models\Invoice::class);
    })->when($this->from_date, fn($q) => 
    $q->whereDate('invoices.invoice_date', '>=', $this->from_date)
)
->when($this->to_date, fn($q) => 
    $q->whereDate('invoices.invoice_date', '<=', $this->to_date)
)

    ->groupBy('invoices.id', 'customers.customer_name', 'invoices.invoice_number', 'invoices.invoice_date');



        $gstr = $query->paginate($this->perPage);
            // dd($gstr);
        return view('livewire.reports.gstr-one-report', [
            'gstr' => $gstr,
            'branches' => Branch::all(),
        ]);
    }
}
