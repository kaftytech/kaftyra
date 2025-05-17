<?php

namespace App\Exports;

use App\Models\StockAdjustment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockAdjustmentsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return StockAdjustment::with(['product', 'branch'])
            ->when($this->filters['branch_id'], fn($q) => $q->where('branch_id', $this->filters['branch_id']))
            ->when($this->filters['product_id'], fn($q) => $q->where('product_id', $this->filters['product_id']))
            ->when($this->filters['type'], fn($q) => $q->where('type', $this->filters['type']))
            ->when($this->filters['from_date'], fn($q) => $q->whereDate('date', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'], fn($q) => $q->whereDate('date', '<=', $this->filters['to_date']))
            ->get()
            ->map(function ($item) {
                return [
                    'Date' => $item->date,
                    'Product' => $item->product->name ?? '-',
                    'Type' => ucfirst($item->type),
                    'Quantity' => $item->quantity,
                    'Branch' => $item->branch->name ?? '-',
                    'Note' => $item->note ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return ['Date', 'Product', 'Type', 'Quantity', 'Branch', 'Note'];
    }
}
