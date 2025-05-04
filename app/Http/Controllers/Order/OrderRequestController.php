<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderRequest;
use App\Models\OrderItem;

class OrderRequestController extends Controller
{
    public function index()
    {
        // Logic to list all order requests
        return view('orders.request.index');   
    }

    public function create()
    {
        // Logic to show the form for creating a new order request
        return view('orders.request.create');  

    }

    public function edit($id)
    {
        // Logic to show the form for editing an existing order request
        $order = OrderRequest::findOrFail($id);
        return view('orders.request.edit', compact('order'));
    }

    public function show($id)
    {
        // Logic to display a specific order request
        $order = OrderRequest::findOrFail($id);
        $orderItems = OrderItem::where('order_request_id', $id)->get();
        return view('orders.request.show', compact('order', 'orderItems'));
    }

    public function store(Request $request)
    {
        // Logic to store a new order request
    }

    public function update(Request $request, $id)
    {
        // Logic to update an existing order request
    }

    public function destroy($id)
    {
        // Logic to delete an existing order request
    }
}
