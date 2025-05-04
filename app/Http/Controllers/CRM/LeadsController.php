<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\Customers;
use App\Models\Vendors;
use App\Notifications\LeadNotification;
use App\Notifications\CustomerNotification;
use App\Models\User;
class LeadsController extends Controller
{
    public function index()
    {
        $leads = Leads::all();
        return view('crm.leads.index', compact('leads'));
    }

    public function create()
    {
        return view('crm.leads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            // Add other validation rules as needed
        ]);

        $input = $request->except('_token');
        $input['status'] = 'new';
       
        // Validate the request data
        // Create a new lead
        $lead = Leads::create($input);

        $usersToNotify = User::whereHas('roles', function ($query) {
                $query->where('name', ['admin', 'store_keeper']);
            })->get();
        foreach ($usersToNotify as $user) {
            $user->notify(new LeadNotification($lead));
        }
        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Leads $lead)
    {
        return view('crm.leads.show', compact('lead'));
    }

    public function edit(Leads $lead)
    {
        return view('crm.leads.edit', compact('lead'));
    }

    public function update(Request $request, Leads $lead)
    {
        $lead->update($request->all());
        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(Leads $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }
    public function convert(Leads $lead, $type)
    {
        if ($type === 'customer') {
            $customer = Customers::create([
                'customer_name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone
            ]);

            $lead->update(['status' => 'converted', 'customer_id' => $customer->id]);

            $usersToNotify = User::whereHas('roles', function ($query) {
                    $query->where('name', ['admin', 'store_keeper']);
                })->get();
            foreach ($usersToNotify as $user) {
                $user->notify(new CustomerNotification($customer));
            }

            return redirect()->back()->with('success', ucfirst($type).' created successfully!');


        } elseif ($type === 'vendor') {
            $vendor = Vendors::create([
                'company_name' => $lead->company_name ?? $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone
            ]);

            $lead->update(['status' => 'converted', 'vendor_id' => $vendor->id]);

            return redirect()->back()->with('success', ucfirst($type).' created successfully!');

        } else {
            return back()->with('error', 'Invalid conversion type.');
        }
        // (Optional) Mark lead as "converted"

    }

    public function logs(Leads $lead)
    {
        return view('crm.leads.logs', compact('lead'));
    }
}
