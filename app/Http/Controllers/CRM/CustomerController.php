<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customers;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use DB;

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
        DB::beginTransaction();

        try {
            $input = $request->all();
            $input['branch_id'] = auth()->user()->currentBranch->id;
            // dd($input['branch_id']);
            // Create customer
            $customer = Customers::create($input);
            // dd($customer);
            // Create related user
            $user = User::create([
                'name' => $input['customer_name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'password' => bcrypt('password'), // Replace with real logic
                'branch_id' => auth()->user()->currentBranch->id
            ]);

            // Assign "customer" role
            $userRole = Role::firstOrCreate(['name' => 'customer']);
            $user->roles()->attach($userRole->id, ['user_type' => \App\Models\User::class]);

            DB::commit();

            return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
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
