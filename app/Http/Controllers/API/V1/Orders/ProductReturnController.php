<?php

namespace App\Http\Controllers\API\V1\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\ProductReturnItems;
use Illuminate\Support\Facades\DB;

class ProductReturnController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming data
        $validated = $request->validate([
            'customer_id' => 'required', // ✅ customer_id required
            'return_date' => 'required|date',
            'reason' => 'nullable|string',
            'return_items.*.product_id' => 'required',
            'return_items.*.quantity' => 'required|integer|min:1'
        ]);
        // dd($validated);
        DB::beginTransaction();

       
        try {
            // Create Product Return
            $productReturn = ProductReturn::create([
                'invoice_id' => null, // ❌ no invoice
                'customer_id' => $validated['customer_id'],
                'return_type' => 'customer', // ✅ fixed to 'customer'
                'return_date' => $validated['return_date'],
                'total_quantity' => 0,
                'total_amount' => 0,
                'status' => 'pending',
                'reason' => $validated['reason'] ?? null,
            ]);

            // Insert Return Items
            $returnItems = [];
            foreach ($validated['return_items'] as $item) {
                // Check if product exists
                $product = Product::find($item['product_id']);
                if (!$product) {
                    DB::rollBack();
                    return response()->json(['error' => 'Product not found: ' . $item['product_id']], 404);
                }
                $price = $product->selling_price; // Assuming you want to use the current price of the product
                $returnItems[] = [
                    'product_return_id' => $productReturn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $price, // Assuming you want to use the current price of the product
                    'total' => $item['quantity'] * $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ProductReturnItems::insert($returnItems);
            // Update total quantity and amount in Product Return
            $productReturn->total_quantity = array_sum(array_column($validated['return_items'], 'quantity'));
            $productReturn->total_amount = array_sum(array_column($returnItems, 'total'));
            $productReturn->save();
            DB::commit();

            return response()->json($productReturn->load('returnItems.product'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create product return: ' . $e->getMessage()], 500);
        }
    }
}
