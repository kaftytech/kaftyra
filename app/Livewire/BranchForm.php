<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class BranchForm extends Component
{
    public $branches;
    public $branch_id;
    public $name, $address, $phone, $email, $city, $state, $country, $zip, $status = 'active';
    public $showForm = false;

    public function mount()
    {
        $this->loadBranches();
    }

    public function render()
    {
        return view('livewire.branch-form');
    }

    public function loadBranches()
    {
        $this->branches = Branch::latest()->get();
    }

    public function resetForm()
    {
        $this->branch_id = null;
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->city = '';
        $this->state = '';
        $this->country = '';
        $this->zip = '';
        $this->status = 'active';
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        $this->branch_id = $branch->id;
        $this->name = $branch->name;
        $this->address = $branch->address;
        $this->phone = $branch->phone;
        $this->email = $branch->email;
        $this->city = $branch->city;
        $this->state = $branch->state;
        $this->country = $branch->country;
        $this->zip = $branch->zip;
        $this->status = $branch->status;
        $this->showForm = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:branches,email,' . $this->branch_id,
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        if ($this->branch_id) {
            $branch = Branch::findOrFail($this->branch_id);
            $branch->update(array_merge($validated, ['updated_by' => Auth::id()]));
        } else {
            Branch::create(array_merge($validated, ['created_by' => Auth::id()]));
        }

        $this->resetForm();
        $this->showForm = false;
        $this->loadBranches();
    }

    public function delete($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        $this->loadBranches();
    }
}
