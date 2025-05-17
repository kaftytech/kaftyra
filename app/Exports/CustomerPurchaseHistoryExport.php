<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class CustomerPurchaseHistoryExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Invoice::with('customer')
            ->where('type', 'locked')
            ->where('status', '!=', 'cancelled')
            ->when($this->filters['customer_id'], fn($q) => $q->where('customer_id', $this->filters['customer_id']))
            ->when($this->filters['from_date'], fn($q) => $q->whereDate('invoice_date', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'], fn($q) => $q->whereDate('invoice_date', '<=', $this->filters['to_date']))
            ->get()
            ->map(function ($item) {
                return [
                    'Date' => $item->invoice_date,
                    'Invoice #' => $item->invoice_number,
                    'Customer' => $item->customer->name ?? 'N/A',
                    'Total' => $item->total_amount,
                    'Paid' => $item->paid_amount,
                    'Due' => $item->due_amount,
                ];
            });
    }

    public function headings(): array
    {
        return ['Date', 'Invoice #', 'Customer', 'Total', 'Paid', 'Due'];
    }
}
