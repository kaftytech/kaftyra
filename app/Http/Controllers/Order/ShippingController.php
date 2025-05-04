<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;

class ShippingController extends Controller
{
    public function index()
    {
        return view('orders.shipping.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'initiate_shipping' => 'required|boolean',
        ]);

        if ($request->initiate_shipping) {
            // Create a delivery record
            Delivery::create([
                'invoice_id' => $request->invoice_id,
                'status' => 'pending'
            ]);

            return redirect()->route('invoices.index')->with('success', 'Delivery Created Successfully!');
        }

        return redirect()->route('invoices.index')->with('info', 'Delivery Not Created.');
    }

}
