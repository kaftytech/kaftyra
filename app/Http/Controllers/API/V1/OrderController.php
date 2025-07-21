<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OrderRequest;
use App\Models\OrderItem;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Account;
use App\Models\AccountTransactions;
use DB;

class OrderController extends Controller
{
    // Get all orders assigned to the salesman
    public function assignedOrders(Request $request)
    {
        $user = $request->user();
        
        $deliveries = Delivery::with(['invoice.customer', 'invoice.items.product'])
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'delivered')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($deliveries);
    }
    
    // Show invoice details
    public function showInvoice(Request $request, Invoice $invoice)
    {
        // Verify the invoice is assigned to this salesman
        $delivery = Delivery::where('invoice_id', $invoice->id)
            ->where('assigned_to', $request->user()->id)
            ->firstOrFail();
            
        $invoice->load(['customer', 'items.product', 'delivery']);
        
        return response()->json($invoice);
    }
    
    // Mark order as delivered
    public function markAsDelivered(Request $request, Invoice $invoice)
    {
        $delivery = Delivery::where('invoice_id', $invoice->id)
            ->where('assigned_to', $request->user()->id)
            ->firstOrFail();
            
        $delivery->update([
            'status' => 'delivered',
            'delivered_date' => now(),
            'delivered_by' => $request->user()->id,
        ]);
        
        return response()->json(['message' => 'Order marked as delivered']);
    }
    
    // Record payment for an invoice
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
        ]);
        
        $user = $request->user();
        
        DB::transaction(function () use ($request, $invoice, $user) {
            $amount = $request->amount;
            
            // Update invoice payment status
            $newPaidAmount = $invoice->paid_amount + $amount;
            $newDueAmount = max(0, $invoice->due_amount - $amount);
            
            $status = 'partial';
            if ($newDueAmount <= 0) {
                $status = 'paid';
            }
            
            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                'status' => $status,
                'payment_method' => $request->payment_method,
            ]);
            
            // Get or create salesman's account
            $account = Account::firstOrCreate(
                ['accountable_id' => $user->id, 'accountable_type' => User::class],
                ['balance' => 0, 'branch_id' => $invoice->branch_id]
            );
            
            // Record transaction
            $openingBalance = $account->balance;
            $closingBalance = $openingBalance + $amount;
            
            AccountTransactions::create([
                'account_id' => $account->id,
                'date' => now(),
                'description' => 'Payment for Invoice #' . $invoice->invoice_number,
                'type' => 'credit',
                'amount' => $amount,
                'opening_balance' => $openingBalance,
                'closing_balance' => $closingBalance,
                'txn_mode' => $request->payment_method,
                'branch_id' => $invoice->branch_id,
            ]);
            
            // Update account balance
            $account->update(['balance' => $closingBalance]);
        });
        
        return response()->json(['message' => 'Payment recorded successfully']);
    }
    
    // Get all order requests created by the salesman
    public function index(Request $request)
    {
        $orders = OrderRequest::with(['customer', 'items.product'])
            ->where('created_by', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($orders);
    }
    
    // Show specific order request
    public function show(Request $request, OrderRequest $orderRequest)
    {
        if ($orderRequest->created_by != $request->user()->id) {
            abort(403, 'Unauthorized');
        }
        
        $orderRequest->load(['customer', 'items.product']);
        
        return response()->json($orderRequest);
    }
    
    // Create new order request
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        
        $user = $request->user();
        
        DB::transaction(function () use ($request, $user) {
            $order = OrderRequest::create([
                'customer_id' => $request->customer_id,
                'request_date' => now(),
                'status' => 'pending',
                'notes' => $request->notes,
                'branch_id' => $user->branch_id,
                'created_by' => $user->id,
            ]);
            
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_request_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });
        
        return response()->json(['message' => 'Order request created successfully'], 201);
    }
}