<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\CreditNote;
use Livewire\WithPagination;

class CreditNoteTable extends Component
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
        $query = CreditNote::orderBy($this->orderBy, $this->orderAsc ? 'DESC' : 'ASC');

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

        $creditNotes = $query->paginate($this->perPage);
        return view('livewire.billing.credit-note-table', [
            'creditNotes' => $creditNotes,
        ]);
    }
}
