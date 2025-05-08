<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class UnitForm extends Component
{
    public $units;
    public $unit_id;
    public $name, $symbol;
    public $showForm = false;

    public function mount()
    {
        $this->loadUnits();
    }

    public function render()
    {
        return view('livewire.inventory.unit-form');
    }

    public function loadUnits()
    {
        $this->units = Unit::latest()->get();
    }

    public function resetForm()
    {
        $this->unit_id = null;
        $this->name = '';
        $this->symbol = '';
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unit_id = $unit->id;
        $this->name = $unit->name;
        $this->symbol = $unit->symbol;
        $this->showForm = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:50',
        ]);

        if ($this->unit_id) {
            Unit::findOrFail($this->unit_id)->update(array_merge($validated, ['updated_by' => Auth::id()]));
        } else {
            Unit::create(array_merge($validated, ['created_by' => Auth::id()]));
        }

        $this->resetForm();
        $this->showForm = false;
        $this->loadUnits();
    }

    public function delete($id)
    {
        Unit::findOrFail($id)->delete();
        $this->loadUnits();
    }
}