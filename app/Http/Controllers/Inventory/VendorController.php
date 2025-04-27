<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendors;
use App\Imports\VendorImport;
use Maatwebsite\Excel\Facades\Excel;

class VendorController extends Controller
{
    public function index()
    {
        return view('inventory.vendors.index');
    }

    public function create()
    {
        return view('inventory.vendors.create');
    }
    public function store(Request $request)
    {
        $vendor = Vendors::create($request->all());

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }
    public function edit($id)
    {
        $vendor = Vendors::findOrFail($id);
        return view('inventory.vendors.edit', compact('vendor'));
    }
    public function update(Request $request, $id)
    {
        $input = $request->except(['_token', '_method']);
        $vendor = Vendors::findOrFail($id);
        $vendor->update($input);

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }
    public function destroy($id)
    {
        $vendor = Vendors::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
    public function show($id)
    {
        $vendor = Vendors::findOrFail($id);
        return view('inventory.vendors.show', compact('vendor'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new VendorImport, $request->file('file'));

        return redirect()->back()->with('success', 'Vendors imported successfully!');
    }
}
