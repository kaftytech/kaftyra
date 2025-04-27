<?php

namespace App\Http\Controllers\API\V1\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderRequest;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderRequestController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'customer_id' => 'required',
            'order_id' => 'required',
            'request_date' => 'required|date',
            'notes' => 'nullable|string',
            'order_items' => 'required|array', // An array of order items
            'order_items.*.product_id' => 'required',
            'order_items.*.quantity' => 'required|integer|min:1',
            'order_items.*.notes' => 'nullable|string',
        ]);
        // dd($validated);

        // Start a database transaction to ensure both the Order Request and Items are created atomically
        DB::beginTransaction();

        try {
            // Create the Order Request (invoice)
            $orderRequest = OrderRequest::create([
                'customer_id' => $validated['customer_id'],
                'order_id' => $validated['order_id'],
                'request_date' => $validated['request_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Prepare Order Items and associate them with the created Order Request
            $orderItems = [];
            foreach ($validated['order_items'] as $item) {
                $orderItems[] = [
                    'order_request_id' => $orderRequest->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Create the Order Items in bulk
            OrderItem::insert($orderItems);

            // Commit the transaction
            DB::commit();

            // Return the response
            return response()->json($orderRequest->load('orderItems.product'), 201); // Including the order items in the response

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            dd($e);
            DB::rollBack();
            return response()->json(['error' => 'Failed to create order request and items: ' . $e->getMessage()], 500);
        }
    }
}
