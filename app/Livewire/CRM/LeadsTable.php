<?php

namespace App\Livewire\CRM;

use Livewire\Component;
use App\Models\Leads;
use Livewire\WithPagination;

class LeadsTable extends Component
{
    use WithPagination;
    protected $listeners = [
        'filtersApplied' => 'applyFilters',
        'filtersReset' => 'resetFilters'
    ];
    
    public $filters = [
        'search' => '',
        'status' => [],
        'category' => '',
        'dateRange' => [
            'start' => '',
            'end' => ''
        ],
        'priceRange' => [
            'min' => '',
            'max' => ''
        ]
    ];
    
    public function applyFilters($filters)
    {
        $this->filters = $filters;

        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->filters = [
            'search' => '',
            'status' => [],
            'dateRange' => [
                'start' => '',
                'end' => ''
            ]
        ];
        $this->resetPage();
    }
    public function render()
    {
        $leads = Leads::query()
            ->when($this->filters['search'], function ($query) {
                $query->where('name', 'like', '%' . $this->filters['search'] . '%');
            })
            ->when($this->filters['search'], function ($query) {
                $query->where('email', 'like', '%' . $this->filters['search'] . '%');
            })
            ->when($this->filters['search'], function ($query) {
                $query->where('phone', 'like', '%' . $this->filters['search'] . '%');
            })
            ->paginate(10);
        // dd($leads);
        return view('livewire.c-r-m.leads-table', [
            'leads' => $leads,
        ]);
    }
}
