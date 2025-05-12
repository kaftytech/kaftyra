<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Vendors;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockSummaryExport;

class StockSummaryTable extends Component
{
    use WithPagination;

    public $branch_id, $category_id, $vendor_id;

    protected $queryString = ['branch_id', 'category_id', 'vendor_id'];

    public function updated($field)
    {
        $this->resetPage();
    }

    public function export()
    {
        return Excel::download(new StockSummaryExport($this->branch_id, $this->category_id, $this->vendor_id), 'stock_summary.xlsx');
    }

    public function render()
    {
        $query = Product::with(['stock', 'category', 'unit']);

        if ($this->branch_id) {
            $query->whereHas('stock', fn($q) => $q->where('branch_id', $this->branch_id));
        }

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        if ($this->vendor_id) {
            $query->where('vendor_id', $this->vendor_id);
        }

        $products = $query->paginate(10);

        return view('livewire.reports.stock-summary-table', [
            'products' => $products,
            'branches' => Branch::all(),
            'categories' => Category::all(),
            'vendors' => Vendors::all(),
        ]);
    }
}
