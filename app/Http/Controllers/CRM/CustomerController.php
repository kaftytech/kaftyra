<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customers;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        return view('crm.customers.index');
    }

    public function create()
    {
        return view('crm.customers.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Customers::create($input);
        // Add other fields as necessary
        // $customers->save();
        return redirect()->route('customers.index')->with('success', 'Lead created successfully.');
    }

    public function show($id)
    {
        $customer = Customers::findOrFail($id);
        return view('crm.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customers = Customers::findOrFail($id);
        // Assuming you want to pass the customer data to the view
        // You can also use a different variable name if needed
   
        return view('crm.customers.edit', compact('customers'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->except(['_token', '_method']);
        $customer = Customers::findOrFail($id);
        $customer->update($input);
        
        return redirect()->route('customers.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy($id)
    {
        // Handle the request to delete the customer
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new CustomerImport, $request->file('file'));

        return redirect()->route('customers.index')->with('success', 'Customers imported successfully.');
    }
}
