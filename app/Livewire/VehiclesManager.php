<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Branch;
use Livewire\Component;

class VehiclesManager extends Component
{
    public $vehicles;

    public $vehicle_id;
    public $vehicle_number;
    public $type;
    public $driver_name;
    public $driver_contact;
    public $notes;
    public $branch_id;

    public $showForm = false;

    public function mount()
    {
        $this->branch_id = auth()->user()->currentBranch->id;
        $this->loadVehicles();
    }

    public function loadVehicles()
    {
        $this->vehicles = Vehicle::where('branch_id', $this->branch_id)->latest()->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $this->vehicle_id = $vehicle->id;
        $this->vehicle_number = $vehicle->vehicle_number;
        $this->type = $vehicle->type;
        $this->driver_name = $vehicle->driver_name;
        $this->driver_contact = $vehicle->driver_contact;
        $this->notes = $vehicle->notes;
        
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'vehicle_number' => 'required|unique:vehicles,vehicle_number,' . $this->vehicle_id,
            'type' => 'required|string|max:100',
            'driver_name' => 'required|string|max:255',
            'driver_contact' => 'required|string|max:20',
        ]);

        Vehicle::updateOrCreate(
            ['id' => $this->vehicle_id],
            [
                'vehicle_number' => $this->vehicle_number,
                'type' => $this->type,
                'driver_name' => $this->driver_name,
                'driver_contact' => $this->driver_contact,
                'notes' => $this->notes,
                'branch_id' => $this->branch_id,
            ]
        );

        $this->resetForm();
        $this->loadVehicles();
        $this->showForm = false;
    }

    public function delete($id)
    {
        Vehicle::findOrFail($id)->delete();
        $this->loadVehicles();
    }

    public function resetForm()
    {
        $this->vehicle_id = null;
        $this->vehicle_number = '';
        $this->type = '';
        $this->driver_name = '';
        $this->driver_contact = '';
        $this->notes = '';
        $this->branch_id = auth()->user()->currentBranch->id; // keep branch_id    
    }

    public function render()
    {
        return view('livewire.vehicles-manager');
    }
}