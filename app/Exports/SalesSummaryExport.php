<?php

namespace App\Exports;

use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesSummaryExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $sales = InvoiceItem::select([
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
            ->when($this->filters['from_date'], fn($q) => $q->whereDate('invoices.invoice_date', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'], fn($q) => $q->whereDate('invoices.invoice_date', '<=', $this->filters['to_date']))
            ->when($this->filters['product_id'], fn($q) => $q->where('invoice_items.product_id', $this->filters['product_id']))
            ->when($this->filters['branch_id'], fn($q) => $q->where('invoices.branch_id', $this->filters['branch_id']))
            ->groupBy('invoice_items.product_id')
            ->with('product')
            ->get();

        return $sales->map(function ($item) {
            return [
                'Product'       => $item->product->name ?? 'N/A',
                'Quantity'      => $item->total_qty,
                'Gross Amount'  => number_format($item->gross_amount, 2),
                'Discount'      => number_format($item->discount_amount, 2),
                'Tax'           => number_format($item->tax_amount, 2),
                'Net Total'     => number_format($item->net_total, 2),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Product',
            'Quantity',
            'Gross Amount',
            'Discount',
            'Tax',
            'Net Total',
        ];
    }
}
