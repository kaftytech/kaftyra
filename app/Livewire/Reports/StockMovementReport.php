<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\StockAdjustment;
use App\Models\Product;
use App\Models\Branch;
use Livewire\WithPagination;
use App\Exports\StockAdjustmentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StockMovementReport extends Component
{
    use WithPagination;

    public $branch_id = '';
    public $product_id = '';
    public $type = '';
    public $from_date = '';
    public $to_date = '';
    public $perPage = 10;

    public function updating($property)
    {
        if (in_array($property, ['branch_id', 'product_id', 'type', 'from_date', 'to_date', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function export()
    {
        $filters = [
            'branch_id' => $this->branch_id,
            'product_id' => $this->product_id,
            'type' => $this->type,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ];

        return Excel::download(new StockAdjustmentsExport($filters), 'stock_movements.xlsx');
    }

    public function render()
    {
        $query = StockAdjustment::with(['product', 'branch'])
            ->when($this->branch_id, fn($q) => $q->where('branch_id', $this->branch_id))
            ->when($this->product_id, fn($q) => $q->where('product_id', $this->product_id))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->from_date, fn($q) => $q->whereDate('date', '>=', $this->from_date))
            ->when($this->to_date, fn($q) => $q->whereDate('date', '<=', $this->to_date))
            ->latest();

        return view('livewire.reports.stock-movement-report', [
            'adjustments' => $query->paginate($this->perPage),
            'branches' => Branch::all(),
            'products' => Product::all(),
        ]);
    }
}
