<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Log;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = "";
    public $orderBy = 'id';
    public $orderAsc = '1';

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
        $query = User::orderBy($this->orderBy, $this->orderAsc ? 'DESC' : 'ASC');

        if (!empty($this->search)) {
            $query->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%')
;
            });
        }

        $users = $query->paginate($this->perPage);
        return view('livewire.admin.user-table', compact('users'));
    }
}
