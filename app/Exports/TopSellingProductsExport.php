<?php

namespace App\Exports;

use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TopSellingProductsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = InvoiceItem::select([
                'invoice_items.product_id',
                DB::raw('SUM(invoice_items.quantity) as total_qty'),
                DB::raw('SUM(invoice_items.net_total) as total_sales')
            ])
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->where('invoices.type', 'locked')
            ->whereNull('invoices.deleted_at')
            ->when($this->filters['from_date'], fn($q) => $q->whereDate('invoices.invoice_date', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'], fn($q) => $q->whereDate('invoices.invoice_date', '<=', $this->filters['to_date']))
            ->when($this->filters['branch_id'], fn($q) => $q->where('invoices.branch_id', $this->filters['branch_id']))
            ->groupBy('invoice_items.product_id')
            ->with('product')
            ->orderByDesc('total_qty') // sorted by quantity sold
            ->get();

        return $query->map(function ($item) {
            return [
                'Product'     => $item->product->name ?? 'N/A',
                'Quantity Sold' => $item->total_qty,
                'Net Sales'   => number_format($item->total_sales, 2),
            ];
        });
    }

    public function headings(): array
    {
        return ['Product', 'Quantity Sold', 'Net Sales'];
    }
}
