<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\ProductReturn;
use App\Models\ProductReturnItems;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class ProductReturnForm extends Component
{
    public $productReturnId;
    public $invoice_id;
    public $customer_id;
    public $return_type = 'manual';
    public $return_date;
    public $discount_type = 'fixed';
    public $discount = 0;
    public $total_quantity = 0;
    public $total_amount = 0;
    public $status = 'pending';
    public $reason;

    public $returnItems = [];
    public $invoices = [];
    public $availableProducts = [];

    // Calculated fields
    public $subtotal = 0;
    public $tax_amount = 0;

    public function mount($productReturnId = null)
    {
        $this->return_date = today()->format('Y-m-d');

        if ($productReturnId) {
            $productReturn = ProductReturn::with('returnItems.product')->findOrFail($productReturnId);
            $this->fill($productReturn->toArray());

            $this->returnItems = $productReturn->returnItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->quantity,
                    'price' => $item->price,
                    'discount_type' => $item->discount_type,
                    'discount' => $item->discount,
                    'tax_percentage' => $item->tax_percentage,
                    'tax_amount' => $item->tax_amount,
                    'total' => $item->total,
                    'reason' => $item->reason,
                ];
            })->toArray();
        } else {
            $this->addReturnItem();
        }
        $this->availableProducts = Product::with('unit')->get();
        $this->invoices = Invoice::all();

    }

    public function addReturnItem()
    {
        $this->returnItems[] = [
            'product_id' => '',
            'quantity' => 1,
            'original_quantity' => 1,
            'price' => 0,
            'discount_type' => 'percentage',
            'discount' => null,
            'tax_percentage' => null,
            'tax_amount' => 0,
            'total' => 0,
            'reason' => '',
        ];
    }

    public function removeReturnItem($index)
    {
        unset($this->returnItems[$index]);
        $this->returnItems = array_values($this->returnItems);
        $this->calculateTotals();
    }

    public function updatedReturnItems()
    {
        $this->calculateItemTotals();
    }
    public function updatedReturnType()
    {
        $this->returnItems = []; 
        $this->addReturnItem();
        $this->calculateTotals();
        $this->customer_id = null;
        $this->invoice_id = null;
    }

    public function calculateTotals()
    {
        $this->total_quantity = collect($this->returnItems)->sum('quantity');
        $this->total_amount = collect($this->returnItems)->sum('total');
    }

    // public function updated($property)
    // {
    //     if (str_contains($property, 'returnItems')) {
    //         $this->calculateItemTotals();
    //     }
    // }
    public function updateOrderInvoiceId($invoiceId) {
        // dd($orderId);
        $invoice = Invoice::find($invoiceId);
        $this->invoice_id = $invoice->id;
        $this->customer_id = $invoice->customer_id;
        $invoiceItem = InvoiceItem::where('invoice_id', $invoiceId)->get();
        foreach($invoiceItem as $index => $item) {
            $this->returnItems[$index]['product_id'] = $item->product->id;
            $this->returnItems[$index]['product_name'] = $item->product->name;
            $this->returnItems[$index]['quantity'] = $item->quantity;
            $this->returnItems[$index]['original_quantity'] = $item->quantity;
            $this->returnItems[$index]['price'] = $item->price;
            $this->returnItems[$index]['total'] = $item->net_total;
            $this->returnItems[$index]['tax_percentage'] = $item->tax_percentage;
            $this->returnItems[$index]['tax_amount'] = $item->tax_amount;
            $this->returnItems[$index]['discount_type'] = $item->discount_type;
            $this->returnItems[$index]['discount'] = $item->discount_type == 'percentage' ? $item->discount : $item->discount_amount;
            $this->returnItems[$index]['discount_amount'] = $item->discount_amount;
            $this->calculateItemTotals($index);

        }
         // Update the order request ID in the invoice items
        $this->returnItems[$index]['invoice_id'] = $invoiceId;
    }
    public function productSelected($index, $productId)
    {
        $product = Product::find($productId);
        if ($product) {
            
            $this->returnItems[$index]['product_id'] = $product->id;
            $this->returnItems[$index]['product_name'] = $product->name;
            $this->returnItems[$index]['price'] = $product->selling_price;
            // $this->returnItems[$index]['hsn_code'] = $product->hsn_code;
            $this->returnItems[$index]['tax_percentage'] = $product->gst_percentage;
            
            $this->calculateItemTotals();
        }
    }

    public function calculateItemTotals()
    {
        foreach ($this->returnItems as $index => &$item) {
            $price = $item['price'];
            $quantity = $item['quantity'];
            $OriginalQuantity = $item['original_quantity'];
            $discountType = $item['discount_type'];
            $total = $price * $quantity;
            $afterDiscount = $total;
            $discountAmount = 0;
            // If $isAddingDiscountAndTax is true, apply the discount and tax calculations
            // if ($this->isAddingDiscountAndTax) {
            // Apply discount based on type
            if ($discountType == 'percentage') {
                $discount = (float)($item['discount'] ?? 0);  // Ensure it's a float
                // dd($total, $discount);              
                $discountRate = floatval($item['discount'] ?? 0); // e.g., 10 for 10%
                $discountPerUnit = ($price * $discountRate) / 100;
                $discountPricePerUnit = $price - $discountPerUnit;
                $afterDiscount = $discountPricePerUnit * $quantity;
                $discountAmount = $discountPerUnit * $quantity;
                
                // dd($item['discount']);
            } elseif ($discountType == 'fixed') {
                // dd($item['discount']);
                $discountPerUnit = $item['discount'] / $OriginalQuantity;
                $discountPricePerUnit = $price - $discountPerUnit;
                $afterDiscount = $discountPricePerUnit * $quantity;
                $discountAmount = $discountPerUnit * $quantity;
                // $item['total'] = $totalDiscount;
                // dd($DiscountperUnit,$item['discount']);
            } else {
                $item['discount'] = 0;
            }

            // Apply GST (tax) calculation
            $item['discount_amount'] = $discountAmount;
            $gstPercentage = !empty($item['tax_percentage']) ? floatval($item['tax_percentage']) : 0;
            $tax = ($total * $gstPercentage) / 100; // GST tax calculation
            $item['tax_amount'] = $tax;

            // Calculate total after applying discount and tax
            $item['total'] = ($afterDiscount + $tax);
        }
        unset($item);
    
        // Call the function to calculate the overall totals (if required)
        $this->calculateTotals();
    }
    
    public function submitReturn()
    {
        $validated = $this->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'customer_id' => 'nullable|exists:customers,id',
            'return_type' => 'required|in:manual,invoice,customer',
            'return_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string',
            'returnItems' => 'required|array|min:1',
            'returnItems.*.product_id' => 'required|exists:products,id',
            'returnItems.*.quantity' => 'required|integer|min:1',
            'returnItems.*.price' => 'required|numeric|min:0',
            'returnItems.*.discount_type' => 'required|in:percentage,fixed,free',
            'returnItems.*.discount' => 'nullable|numeric|min:0',
            'returnItems.*.tax_percentage' => 'nullable|string',
            'returnItems.*.tax_amount' => 'nullable|numeric|min:0',
            'returnItems.*.total' => 'required|numeric|min:0',
            'returnItems.*.reason' => 'nullable|string',
        ]);

        $productReturn = ProductReturn::updateOrCreate(
            ['id' => $this->productReturnId],
            [
                'invoice_id' => $this->invoice_id ?? null,
                'customer_id' => $this->customer_id ?? null,
                'return_type' => $this->return_type,
                'return_date' => $this->return_date,
                'total_quantity' => $this->total_quantity,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
                'reason' => $this->reason,
                'created_by' => Auth::id()
            ]
        );
        // Create invoice items
        foreach ($this->returnItems as $item) {
            ProductReturnItems::updateOrCreate(
                ['id' => $item['id'] ?? null], // Update if id exists
                [
                    'product_return_id' => $productReturn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'discount_type' => $item['discount_type'] ?? 'fixed',
                    'discount' => $item['discount_type'] == 'percentage' ? $item['discount'] : 0,
                    'discount_amount' => $item['discount_amount'],
                    'tax_percentage' => floatval($item['tax_percentage'] ?? 0),
                    'tax_amount' => $item['tax_amount'] ?? 0,
                    'total' => $item['total'] ?? 0,
                ]
            );
            // if($this->invoiceType == 'final')
            // {
                // Update stock
                Stock::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'type' => 'in',
                    'stock_type' => 'return',
                    'reference_id' => $productReturn->id,
                    'date' => now(),
                    'note' => 'Return',
                ]);
            // }
        }

        session()->flash('success', 'Product Return saved successfully.');

        return redirect()->route('product-returns.index');
    }

    public function render()
    {
        return view('livewire.orders.product-return-form', [
            'customers' => Customers::all(),
            'products' => Product::all(),
        ]);
    }
}
