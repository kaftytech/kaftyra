<?php

namespace App\Livewire\CRM;

use Livewire\Component;
use App\Models\Leads;
use Livewire\WithPagination;

class LeadsTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = "";
    public $orderBy = 'id';
    public $orderAsc = '1';
    public $status = 'all';

    public function sortBy($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderBy = $column;
            $this->orderAsc = true;
        }
    }

    public function render()
    {
        $query = Leads::orderBy($this->orderBy, $this->orderAsc ? 'DESC' : 'ASC');

        if (!empty($this->search)) {
            $query->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $leads = $query->paginate($this->perPage);
        // dd($leads);
        return view('livewire.c-r-m.leads-table', [
            'leads' => $leads,
        ]);
    }
}
