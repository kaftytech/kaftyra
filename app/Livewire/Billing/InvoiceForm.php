<?php

namespace App\Livewire\Billing;

use App\Models\Customers;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OrderRequest;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceForm extends Component
{
    public $currentStep = 1;
    public $invoice;
    public $invoiceId;
    public $invoiceType = 'draft'; // draft, quotation, locked
    public $invoiceItems = [];
    public $availableProducts = [];
    public $customers = [];
    public $orders = [];
    public $productSearch = '';
    
    // Invoice fields
    public $invoice_number;
    public $customer_id;
    public $invoice_date;
    public $discount_type = 'fixed';
    public $discount = 0;
    public $tax_type = 'GST';
    public $tax_percentage = 0;
    public $notes;
    public $payment_method;
    public $currency = 'INR';
    public $paid_amount = 0;
    
    // Calculated fields
    public $subtotal = 0;
    public $tax_amount = 0;
    public $total_amount = 0;
    public $due_amount = 0;
    
    protected $rules = [
        'invoice_number' => 'required|unique:invoices,invoice_number',
        'customer_id' => 'required|exists:customers,id',
        'invoice_date' => 'required|date',
        'discount_type' => 'nullable|in:fixed,percentage',
        'discount' => 'nullable|numeric|min:0',
        'tax_type' => 'nullable|string',
        'tax_percentage' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
        'payment_method' => 'nullable|string',
        'paid_amount' => 'numeric|min:0',
        'invoiceItems' => 'required|array|min:1',
        'invoiceItems.*.product_id' => 'required|exists:products,id',
        'invoiceItems.*.quantity' => 'required|numeric|min:1',
        'invoiceItems.*.price' => 'required|numeric|min:0',
    ];
    
    protected $listeners = ['productSelected'];
    
   
    public function updatedProductSearch($value)
    {
        if (strlen($value) >= 3) {
            $this->availableProducts = Product::where('name', 'like', '%' . $value . '%')
                ->orWhere('product_code', 'like', '%' . $value . '%')
                ->limit(10)
                ->get();
        } else {
            $this->availableProducts = [];
        }
    }
    public function mount($invoiceId = null)
    {
        $this->invoice_date = Carbon::now()->format('Y-m-d');
        // $this->invoice_number = 'INV-' . time();
        $this->customers = Customers::orderBy('id')->get();
        $this->orders = OrderRequest::orderBy('id')->get();
        $this->availableProducts = Product::with('unit')->get();

        if ($invoiceId) {
            $this->loadInvoice($invoiceId);
        } else {
            $this->invoice_number = 'INV-' . time();
            $this->addItem();
        }
    }
    
    public function render()
    {
        return view('livewire.billing.invoice-form');
    }
    public function loadInvoice($invoiceId)
    {
        $this->invoice = Invoice::with('items.product')->findOrFail($invoiceId);

        $this->invoice_number = $this->invoice->invoice_number;
        $this->customer_id = $this->invoice->customer_id;
        $this->invoice_date = $this->invoice->invoice_date;
        $this->discount_type = $this->invoice->discount_type;
        $this->discount = $this->invoice->discount;
        $this->tax_type = $this->invoice->tax_type;
        $this->tax_percentage = $this->invoice->tax_percentage;
        $this->notes = $this->invoice->notes;
        $this->payment_method = $this->invoice->payment_method;
        $this->currency = $this->invoice->currency;
        $this->paid_amount = $this->invoice->paid_amount;

        // Load invoice items
        $this->invoiceItems = [];
        foreach ($this->invoice->items as $item) {
            $this->invoiceItems[] = [
                'id' => $item->id,
                'order_request_id' => $item->order_request_id ?? null,
                'product_code' => $item->product->product_code ?? '',
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? '',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
                'discount_type' => $item->discount_type ?? 'fixed',
                'discount' => $item->discount ?? 0,
                'tax_percentage' => $item->tax_percentage ?? 0,
                'tax_amount' => $item->tax_amount ?? 0,
                'net_total' => $item->net_total ?? 0,
                'available_stock' => $item->product->available_stock ?? 0,
            ];
        }

        // After loading, recalculate totals
        $this->calculateInvoice();
    }

    public function addItem()
    {
        $this->invoiceItems[] = [
            'product_id' => '',
            'product_name' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
            'discount' => 0,
            'tax' => 0,
            'net_total' => 0,
            'available_stock' => 0,
            // 'hsn_code' => '',
            'tax_percentage' => 0,
        ];
    }
    
    public function removeItem($index)
    {
        if (count($this->invoiceItems) > 1) {
            unset($this->invoiceItems[$index]);
            $this->invoiceItems = array_values($this->invoiceItems);
            $this->calculateInvoice();
        }
    }

    public function updateOrderRequestId($orderId) {
        // dd($orderId);
        $order = OrderRequest::find($orderId);
        $this->customer_id = $order->customer_id;
        $orderItem = OrderItem::where('order_request_id', $orderId)->get();
        foreach($orderItem as $index => $item) {
            $this->invoiceItems[$index]['product_id'] = $item->product->id;
            $this->invoiceItems[$index]['product_name'] = $item->product->name;
            $this->invoiceItems[$index]['quantity'] = $item->quantity;
            $this->invoiceItems[$index]['price'] = $item->product->selling_price;
            $this->invoiceItems[$index]['available_stock'] = $item->product->available_stock;
            $this->invoiceItems[$index]['total'] = $item->quantity * $item->product->selling_price;
            $this->invoiceItems[$index]['tax_percentage'] = $item->product->gst_percentage;
            $this->calculateItemTotal($index);

        }
         // Update the order request ID in the invoice items
        $this->invoiceItems[$index]['order_request_id'] = $orderId;
    }
    
    public function productSelected($index, $productId)
    {
        $product = Product::find($productId);
        if ($product) {
            // Get available stock
            $stockIn = Stock::where('product_id', $productId)
                ->where('type', 'in')
                ->sum('quantity');
            $stockOut = Stock::where('product_id', $productId)
                ->where('type', 'out')
                ->sum('quantity');
            $availableStock = $stockIn - $stockOut;
            
            $this->invoiceItems[$index]['product_id'] = $product->id;
            $this->invoiceItems[$index]['product_name'] = $product->name;
            $this->invoiceItems[$index]['price'] = $product->selling_price;
            $this->invoiceItems[$index]['available_stock'] = $availableStock;
            // $this->invoiceItems[$index]['hsn_code'] = $product->hsn_code;
            $this->invoiceItems[$index]['tax_percentage'] = $product->gst_percentage;
            
            $this->calculateItemTotal($index);
        }
    }
    
    public function updatedInvoiceItems($value, $index)
    {
        $parts = explode('.', $index);
        
        // Now expecting format: '0.quantity' => ['0', 'quantity']
        if (count($parts) == 2 && in_array($parts[1], ['quantity','price' ,'discount'])) {
            $this->calculateItemTotal($parts[0]);
        }
    }

    
    public function updatedDiscount()
    {
        $this->calculateInvoice();
    }
    
    public function updatedDiscountType()
    {
        $this->calculateInvoice();
    }
    
    public function updatedTaxPercentage()
    {
        $this->calculateInvoice();
    }
    
    public function updatedPaidAmount()
    {
        $this->calculateDueAmount();
    }
    
    public function calculateItemTotal($index)
    {
        $quantity = $this->invoiceItems[$index]['quantity'];
        $price = $this->invoiceItems[$index]['price'];
        $discountType = $this->invoiceItems[$index]['discount_type'] ?? 'fixed';
        $discount = $this->invoiceItems[$index]['discount'] ?? 0;
        $total = $quantity * $price;
        $discountPercentage = '';
        $discountAmount = 0;
        if ($discountType == 'percentage') {
            $discount = (float)($discount ?? 0);  // Ensure it's a float
            // dd($total, $discount);
            $discountPercentage = $discount;
            $discountAmount = ($total * $discount) / 100;  // Apply the discount
            // dd($item['discount']);
        }
         elseif ($discountType == 'fixed') {
            $discountAmount = $discount ?? 0;
        } else {
            $discountAmount = 0;
        }
        // Calculate tax if GST percentage exists
        $gstPercentage = !empty($this->invoiceItems[$index]['tax_percentage']) ? 
            floatval($this->invoiceItems[$index]['tax_percentage']) : 0;
            
        $afterDiscount = $total - $discountAmount;
        $tax = ($total * $gstPercentage) / 100;
        $priceAfterTax = $price + ($price * $gstPercentage) / 100;

        $this->invoiceItems[$index]['total'] = $total;
        $this->invoiceItems[$index]['discount_type'] = $discountType;
        $this->invoiceItems[$index]['discount'] = $discountType == 'percentage' ? $discountPercentage : $discountAmount;
        $this->invoiceItems[$index]['discount_amount'] = $discountAmount;
        $this->invoiceItems[$index]['tax_percentage'] = $gstPercentage;
        $this->invoiceItems[$index]['tax_amount'] = $tax;
        $this->invoiceItems[$index]['price_after_tax_and_discount'] = $priceAfterTax;
        $this->invoiceItems[$index]['net_total'] = $afterDiscount + $tax;
        
        $this->calculateInvoice();
    }
    
    public function calculateInvoice()
    {
        $this->subtotal = 0;
        $itemTaxTotal = 0;
        
        foreach ($this->invoiceItems as $item) {
            if (!empty($item['net_total'])) {
                $this->subtotal += $item['total'] - ($item['discount'] ?? 0) + ($item['tax_amount'] ?? 0);  // Add $item['tax_amount'];
            }
        }
        
        // Calculate global discount
        $globalDiscount = 0;
        if ($this->discount > 0) {
            if ($this->discount_type == 'percentage') {
                $globalDiscount = ($this->subtotal * $this->discount) / 100;
            } else {
                $globalDiscount = $this->discount;
            }
        }
        
        // Calculate global tax
        $globalTax = 0;
        if ($this->tax_percentage > 0) {
            $globalTax = (($this->subtotal - $globalDiscount)) / 100;
        }
        
        $this->tax_amount = $itemTaxTotal + $globalTax;
        $this->total_amount = $this->subtotal - $globalDiscount + $this->tax_amount;
        
        $this->calculateDueAmount();
    }
    
    public function calculateDueAmount()
    {
        $this->due_amount = $this->total_amount - $this->paid_amount;
    }
    
    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'invoiceItems' => 'required|array|min:1',
                'invoiceItems.*.product_id' => 'required',
                'invoiceItems.*.quantity' => 'required|numeric|min:1',
            ]);
            
            // Check if items have sufficient stock
            foreach ($this->invoiceItems as $index => $item) {
                $stockIn = Stock::where('product_id', $item['product_id'])
                    ->where('type', 'in')
                    ->sum('quantity');
                $stockOut = Stock::where('product_id', $item['product_id'])
                    ->where('type', 'out')
                    ->sum('quantity');
                $availableStock = $stockIn - $stockOut;
                
                if ($item['quantity'] > $availableStock) {
                    $product = Product::find($item['product_id']);
                    $this->addError('invoiceItems.' . $index . '.quantity', 
                        "Insufficient stock for {$product->name}. Available: {$availableStock}");
                    return;
                }
            }
        }
        
        $this->currentStep++;
    }
    
    public function prevStep()
    {
        $this->currentStep--;
    }
    
    public function createInvoice()
    {
        // $this->validate();
        // dd($this->invoiceItems);
        DB::beginTransaction();
        try {
            // Create invoice
            $status = $this->paid_amount >= $this->total_amount ? 'paid' : 
                     ($this->paid_amount > 0 ? 'partial' : 'unpaid');
            
            $invoice = Invoice::updateOrCreate(
                ['id' => $this->invoiceId ?? null],
                [
                    'invoice_number' => $this->invoice_number,
                    'customer_id' => $this->customer_id,
                    'total_amount' => $this->total_amount,
                    'discount_type' => $this->discount_type,
                    'discount' => $this->discount,
                    'tax_type' => $this->tax_type,
                    'tax_percentage' => $this->tax_percentage,
                    'tax_amount' => $this->tax_amount,
                    'subtotal' => $this->subtotal,
                    'paid_amount' => $this->paid_amount,
                    'due_amount' => $this->due_amount,
                    'invoice_date' => $this->invoice_date,
                    'type' => $this->invoiceType,
                    'is_locked' => $this->invoiceType == 'final' ? true : false,
                    'status' => $status,
                    'notes' => $this->notes,
                    'currency' => $this->currency,
                    'payment_method' => $this->payment_method,
                    'seller_id' => Auth::id(),
                ]
            );            
            // Create invoice items
            foreach ($this->invoiceItems as $item) {
                InvoiceItem::updateOrCreate(
                    ['id' => $item['id'] ?? null], // Update if id exists
                    [
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['total'],
                        'discount_type' => $item['discount_type'] ?? 'fixed',
                        'discount' => $item['discount_type'] == 'percentage' ? $item['discount'] : 0,
                        'discount_amount' => $item['discount_amount'] ?? 0,
                        'tax_percentage' => floatval($item['tax_percentage'] ?? 0),
                        'tax_amount' => $item['tax_amount'] ?? 0,
                        'price_after_tax_and_discount' => $item['price_after_tax_and_discount'] ?? 0,
                        'net_total' => $item['net_total'],
                    ]
                );
                if($this->invoiceType == 'final')
                {
                    // Update stock
                    Stock::create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'type' => 'out',
                        'stock_type' => 'sale',
                        'reference_id' => $invoice->id,
                        'date' => now(),
                        'note' => 'Invoice #' . $invoice->invoice_number,
                    ]);
                }
            }

            if(!$this->invoiceId && $this->invoiceType == 'final')
            {
                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $this->paid_amount,
                    'payment_method' => $this->payment_method,
                    'payment_date' => now(),
                    'status' => 'completed',
                    'notes' => $this->notes,
                    'created_by' => Auth::id()               
                ]);
            }
            
            DB::commit();
            
            $this->invoice = $invoice;
            $invoice->load('customer', 'items.product.unit');
            if($this->invoiceType == 'draft')
            {
                return redirect()->route('invoices.index')->with('success', 'Invoice created successfully as a draft.');
            }
            elseif($this->invoiceType == 'quotation')
            {
                return redirect()->route('invoices.index')->with('success', 'Invoice created successfully as a quotation.');
            }
            elseif($this->invoiceType == 'final')
            {
                $this->currentStep = 3;
            }
            
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $this->addError('general', 'Failed to create invoice: ' . $e->getMessage());
        }
    }
    
    public function printInvoice()
    {
        return redirect()->route('invoices.print', $this->invoice->id);
    }
    
    public function downloadPdf()
    {
        return redirect()->route('invoices.pdf', $this->invoice->id);
    }
    
    public function resetForm()
    {
        $this->reset([
            'currentStep', 'invoiceItems', 'invoice_number', 'customer_id',
            'discount_type', 'discount', 'tax_type', 'tax_percentage', 'notes',
            'payment_method', 'paid_amount', 'subtotal', 'tax_amount',
            'total_amount', 'due_amount', 'invoice'
        ]);
        
        $this->invoice_date = Carbon::now()->format('Y-m-d');
        $this->invoice_number = 'INV-' . time();
        $this->addItem();
        $this->currentStep = 1;
    }
}