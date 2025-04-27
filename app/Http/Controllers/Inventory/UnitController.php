<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit; // Assuming you have a Unit model

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('id', 'desc')->paginate(10); // Fetch all units from the database
        return view('inventory.units.index', compact('units'));
    }

    public function create()
    {
        return view('inventory.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:1000',
            // Add other validation rules as needed
        ]);

        $input = $request->except('_token');

        // Create a new unit
        // Assuming you have a Unit model
        Unit::create($input);

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function show($id)
    {
        return view('inventory.units.show', compact('id'));
    }

    public function edit($id)
    {
        // Assuming you have a Unit model
        $unit = Unit::findOrFail($id);
        $units = Unit::paginate(10); // Fetch all units from the database

        return view('inventory.units.index', compact('unit','units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:1000',
            // Add other validation rules as needed
        ]);

        $input = $request->except('_token');

        // Assuming you have a Unit model
        $unit = Unit::findOrFail($id);
        $unit->update($input);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy($id)
    {
        // Assuming you have a Unit model
        // $unit = Unit::findOrFail($id);
        // $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
