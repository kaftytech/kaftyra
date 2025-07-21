<?php

namespace App\Exports;

use App\Models\InvoiceItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class GstrOneExport implements FromView
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = InvoiceItem::select([
                'invoice_items.product_id',
                'invoices.invoice_date',
                'invoices.invoice_number',
                'customers.name as customer_name',
                'customers.gstin as customer_gstin',
                'customers.state as customer_state',
                'products.hsn_code',
                DB::raw('SUM(invoice_items.quantity) as qty'),
                DB::raw('SUM(invoice_items.total - invoice_items.discount_amount) as taxable_value'),
                DB::raw('SUM(CASE WHEN taxes.tax_name = "CGST" THEN taxes.amount ELSE 0 END) as cgst'),
                DB::raw('SUM(CASE WHEN taxes.tax_name = "SGST" THEN taxes.amount ELSE 0 END) as sgst'),
                DB::raw('SUM(CASE WHEN taxes.tax_name = "IGST" THEN taxes.amount ELSE 0 END) as igst'),
                DB::raw('SUM(invoice_items.net_total) as total_invoice_value'),
            ])
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->join('products', 'products.id', '=', 'invoice_items.product_id')
            ->leftJoin('taxables as taxes', function ($join) {
                $join->on('taxes.taxable_id', '=', 'invoice_items.id')
                     ->where('taxes.taxable_type', '=', InvoiceItem::class);
            })
            ->where('invoices.type', 'locked')
            ->whereNull('invoices.deleted_at')
            ->when($this->filters['from_date'], fn($q) => $q->whereDate('invoices.invoice_date', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'], fn($q) => $q->whereDate('invoices.invoice_date', '<=', $this->filters['to_date']))
            ->when($this->filters['branch_id'], fn($q) => $q->where('invoices.branch_id', $this->filters['branch_id']))
            ->groupBy([
                'invoice_items.product_id',
                'invoices.invoice_date',
                'invoices.invoice_number',
                'customers.name',
                'customers.gstin',
                'customers.state',
                'products.hsn_code'
            ])
            ->get();

        return view('exports.gstr-one', ['data' => $query]);
    }
}
