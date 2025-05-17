<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockSummaryExport implements FromCollection, WithHeadings
{
    protected $branch_id, $category_id;

    public function __construct($branch_id, $category_id)
    {
        $this->branch_id = $branch_id;
        $this->category_id = $category_id;
    }

    public function collection()
    {
        $query = Product::with(['stock', 'category', 'unit']);

        if ($this->branch_id) {
            $query->whereHas('stock', fn($q) => $q->where('branch_id', $this->branch_id));
        }

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        return $query->get()->map(function ($product) {
            return [
                $product->name,
                $product->category->name ?? '-',
                // $product->vendor->name ?? '-',
                // $product->unit->name ?? '-',
                // $product->mrp,
                // $product->selling_price,
                $product->stock->current_stock ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return ['Product', 'Category', 'Current Stock'];
    }
}
