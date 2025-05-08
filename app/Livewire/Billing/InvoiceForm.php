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
use App\Models\StockAdjustment;
use App\Models\TaxSetting;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Log;
use App\Traits\HandlesProductSelection;
use App\Traits\HandlesCustomerSelection;
use App\Traits\HandlesOrderRequestSelection;
class InvoiceForm extends Component
{
    use HandlesProductSelection, HandlesCustomerSelection, HandlesOrderRequestSelection;
    public $currentStep = 1;
    public $invoice;
    public $invoiceId;
    public $invoiceType = 'draft'; // draft, quotation, locked
    public $invoiceItems = [];
    public $availableProducts = [];
    public $customers = [];
    public $orders = [];
    public $orderId;
    public $productSearch = '';
    
    // Invoice fields
    public $invoice_number;
    public $customer_id;
    public $invoice_date;
    public $discount_type = 'fixed';
    public $discount = 0;
    // public $tax_type = 'GST';
    // public $tax_percentage = 0;
    public $taxes = []; // All active tax settings
    public $selectedTaxes = []; // e.g., ['CGST' => true, 'SGST' => false]
    public $taxValues = []; // e.g., ['CGST' => 9.00, 'SGST' => 9.00]
    public $taxablesData = [];
    public $notes;
    public $payment_method;
    public $currency = 'INR';
    public $paid_amount = 0;
    
    // Calculated fields
    public $subtotal = 0;
    public $tax_amount = 0;
    public $total_amount = 0;
    public $due_amount = 0;
    public $company;
    
    public $product_id;
    public $customer;

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
    public function mount($invoiceId = null, $orderId = null)
    {
        $this->invoice_date = Carbon::now()->format('Y-m-d');
        // $this->invoice_number = 'INV-' . time();
        $this->orders = OrderRequest::orderBy('id')->get();
        $this->availableProducts = Product::with('unit')->get();
        $this->company = Company::first();
        if ($invoiceId) {
            $this->loadInvoice($invoiceId);

             // Load taxes from taxables table
            $invoice = Invoice::with('taxables')->find($invoiceId);
            $this->total_amount = $this->invoice->total_amount;
            $this->due_amount = $this->invoice->due_amount;
            $this->customer_id = $this->invoice->customer_id;
            $this->search_by_query = $this->invoice->customer->customer_name . ' | ' . $this->invoice->customer->phone;
            $this->customer = $this->invoice->customer;
            $this->search_by_query_status = 'selected';

            $this->taxes = TaxSetting::where('is_active', true)->get();
            foreach ($this->taxes as $tax) {
                $matched = $invoice->taxables->firstWhere('tax_name', $tax->name);
                $this->selectedTaxes[$tax->name] = $matched ? true : false;
                $this->taxValues[$tax->name] = $matched ? $matched->rate : $tax->rate; // use stored rate or default
            }
        } else {
            $this->taxes = TaxSetting::where('is_active', true)->get();

            foreach ($this->taxes as $tax) {
                $this->selectedTaxes[$tax->name] = false; // initially unselected
                $this->taxValues[$tax->name] = $tax->rate; // default from DB
            }
            $this->addItem();
        }
        if($orderId)
        {
            $this->updateOrderRequestId($orderId);
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

        $this->invoiceType = $this->invoice->is_locked == true ? 'final' : $this->invoice->type;
        // Load invoice items
        $this->invoiceItems = [];
        foreach ($this->invoice->items as $item) {
            $this->invoiceItems[] = [
                'id' => $item->id,
                'order_request_id' => $item->order_request_id ?? null,
                'product_code' => $item->product->product_code ?? '',
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? '',
                'product_search' => $item->product->name . ' (' . $item->product->product_code . ')',
                'search_status' => 'selected',
                'quantity' => (int) $item->quantity, // Cast to integer
                'price' => (float) $item->price, // Cast to float
                'total' => (float) $item->total, // Cast to float
                'discount_type' => $item->discount_type ?? 'fixed',
                'discount' => $item->discount_type == 'percentage' ? (float) $item->discount : 0, // Cast to float
                'discount_amount' => (float) $item->discount_amount ?? 0, // Cast to float
                'tax_percentage' => (float) $item->tax_percentage ?? 0, // Cast to float
                'tax_amount' => (float) $item->tax_amount ?? 0, // Cast to float
                'net_total' => (float) $item->net_total ?? 0, // Cast to float
                'available_stock' => (int) $item->product->currentStock(), // Cast to integer
                'price_after_tax' => (float) $item->price_after_tax ?? 0, // Cast to float
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
            'product_search' => '',
            'search_results' => [],
            'search_status' => '',
            'highlight_index' => 0,
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
        $this->order_search_by_query = $order->order_id;
        $this->order_search_by_query_status = 'selected';
        $this->search_by_query = $order->customer->customer_name . ' | ' . $order->customer->phone;
        $this->customer = $order->customer;
        $this->search_by_query_status = 'selected';
        $orderItem = OrderItem::where('order_request_id', $orderId)->get();
        foreach($orderItem as $index => $item) {
            $this->invoiceItems[$index]['product_id'] = $item->product->id;
            $this->invoiceItems[$index]['product_name'] = $item->product->name;
            $this->invoiceItems[$index]['product_code'] = $item->product->product_code;
            $this->invoiceItems[$index]['quantity'] = $item->quantity;
            $this->invoiceItems[$index]['price'] = $item->product->selling_price;
            $this->invoiceItems[$index]['available_stock'] = $item->product->currentStock();
            $this->invoiceItems[$index]['total'] = $item->quantity * $item->product->selling_price;
            $this->invoiceItems[$index]['tax_percentage'] = $item->product->gst_percentage;
            $this->invoiceItems[$index]['product_search'] = $item->product->name . ' (' . $item->product->product_code . ')';
            $this->invoiceItems[$index]['search_status'] = 'selected';

            $this->calculateItemTotal($index);

        }
         // Update the order request ID in the invoice items
        $this->invoiceItems[$index]['order_request_id'] = $orderId;
    }
    
    public function updatedInvoiceItems($value, $index)
    {
        $parts = explode('.', $index);
    
        // Handle quantity, price, discount update
        if (count($parts) == 2 && in_array($parts[1], ['quantity', 'price', 'discount'])) {
            $this->calculateItemTotal($parts[0]);
        }
    
        // Handle product search
        if (count($parts) == 2 && $parts[1] === 'product_search') {
            $this->handleProductSearchWrapper($parts[0], $value);
        }
    }
        
    public function handleProductSearchWrapper($index, $query)
    {
        $this->handleProductSearch($this->invoiceItems, $index, $query);
    }

    public function selectProductWrapper($index, $productId)
    {
        $error = $this->selectProduct(
            $this->invoiceItems,
            $index,
            $productId,
            fn($i) => $this->calculateItemTotal($i)
        );

        if ($error) {
            $this->addError("invoiceItems.$index.product_id", $error);
        }
    }


    public function highlightProductNextWrapper($index)
    {
        $this->highlightProductNext($this->invoiceItems, $index);
    }

    public function highlightProductPreviousWrapper($index)
    {
        $this->highlightProductPrevious($this->invoiceItems, $index);
    }

    public function selectHighlightedProductWrapper($index)
    {
        $this->selectHighlightedProduct($this->invoiceItems, $index, fn($i, $id) => $this->selectProductWrapper($i, $id));
    }

    // Method to handle customer search and update results
   
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

    public function updatedSelectedTaxes()
    {
        $this->calculateInvoice();
    }

    public function updatedTaxValues()
    {
        $this->calculateInvoice();
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
        $this->invoiceItems[$index]['price_after_tax'] = $priceAfterTax;
        $this->invoiceItems[$index]['net_total'] = $afterDiscount + $tax;
        
        $this->calculateInvoice();
    }

    public function calculateTax($taxName)
    {
        // If not selected, return 0
        if (!($this->selectedTaxes[$taxName] ?? false)) {
            return 0;
        }
        
        // Tax rate
        $rate = $this->taxValues[$taxName] ?? 0;

        // Subtotal after discount
        $baseAmount = $this->subtotal;

        // Apply global discount to the base
        if ($this->discount > 0) {
            $discount = $this->discount_type == 'percentage'
                ? ($baseAmount * $this->discount / 100)
                : $this->discount;

            $baseAmount -= $discount;
        }

        // Calculate tax amount
        return ($baseAmount * $rate) / 100;
    }

    
    public function calculateInvoice()
    {
        $this->subtotal = 0;
        $itemTaxTotal = 0;
    
        // 1. Calculate subtotal from items
        foreach ($this->invoiceItems as $item) {
            if (!empty($item['net_total'])) {
                $this->subtotal += $item['net_total'];
            }
        }
    
        // 2. Calculate global discount
        $globalDiscount = 0;
        if ($this->discount > 0) {
            if ($this->discount_type == 'percentage') {
                $globalDiscount = ($this->subtotal * $this->discount) / 100;
            } else {
                $globalDiscount = $this->discount;
            }
        }
    
        // 3. Calculate global tax (loop through selected taxes)
        $globalTax = 0;
        if (!empty($this->selectedTaxes)) {
            foreach ($this->selectedTaxes as $taxName => $isSelected) {
                if ($isSelected) {
                    $rate = $this->taxValues[$taxName] ?? 0;
                    $globalTax += (($this->subtotal - $globalDiscount) * $rate) / 100;

                   // Ensure tax is added only once
                    if (!in_array($taxName, array_column($this->taxablesData, 'tax_name'))) {
                        $this->taxablesData[] = [
                            'tax_setting_id' => TaxSetting::where('name', $taxName)->value('id'),
                            'tax_name' => $taxName,
                            'rate' => $rate,
                            'amount' => $globalTax,
                        ];
                    }
                }
            }
        }
    
        // 4. Final amounts
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
                $product = Product::find($item['product_id']);
                $availableStock = $product->currentStock();
                // dd($availableStock);
                // Get the existing item if editing
                $existingItem = isset($item['id']) ? InvoiceItem::find($item['id']) : null;

                if ($existingItem) {
                    // Editing existing item
                    $reversedStock = $existingItem->quantity; // Add back old quantity
                    $requiredStock = $item['quantity']; // New required
                    $finalStock = $availableStock + $reversedStock; // stock after reversal
                    // dd($reversedStock, $requiredStock, $availableStock, $finalStock);

                    if ($requiredStock > $finalStock) {
                        $this->addError('invoiceItems.' . $index . '.quantity', 
                            "Insufficient stock for {$product->name}. Available: {$finalStock}");
                        return;
                    }
                } else {
                    // New item
                    if ($item['quantity'] > $availableStock) {
                        $this->addError('invoiceItems.' . $index . '.quantity', 
                            "Insufficient stock for {$product->name}. Available: {$availableStock}");
                        return;
                    }
                }

            }
        }
        
        $this->currentStep++;
    }
                    
    public function prevStep()
    {
        $this->currentStep--;
    }
    
    // public function createInvoice()
    // {
    //     // $this->validate();
    //     // dd($this->invoiceItems);
    //     DB::beginTransaction();
    //     try {
    //         // Create invoice
    //         // $status = $this->paid_amount >= $this->total_amount ? 'paid' : 
    //         //          ($this->paid_amount > 0 ? 'partial' : 'unpaid');
            
    //         $invoice = Invoice::updateOrCreate(
    //             ['id' => $this->invoiceId ?? null],
    //             [
    //                 'customer_id' => $this->customer_id,
    //                 'total_amount' => $this->total_amount,
    //                 'discount_type' => $this->discount_type,
    //                 'discount' => $this->discount,
    //                 'tax_type' => $this->tax_type,
    //                 'tax_percentage' => $this->tax_percentage,
    //                 'tax_amount' => $this->tax_amount,
    //                 'subtotal' => $this->subtotal,
    //                 // 'paid_amount' => $this->paid_amount,
    //                 'due_amount' => $this->due_amount,
    //                 'invoice_date' => $this->invoice_date,
    //                 'type' => $this->invoiceType,
    //                 'is_locked' => $this->invoiceType == 'locked' ? true : false,
    //                 // 'status' => $status,
    //                 'notes' => $this->notes,
    //                 // 'currency' => $this->currency,
    //                 'payment_method' => $this->payment_method,
    //                 'seller_id' => Auth::id(),
    //             ]
    //         );            
    //         // 2. If editing, revert previous stock first
    //         if ($this->invoiceId && $this->invoiceType == 'locked') {
    //             foreach ($invoice->items as $oldItem) {
    //                 $product = Product::find($oldItem->product_id);
    //                 $product->stockIn($oldItem->quantity, $invoice, 'Revert previous invoice stock');
    //             }
    //             $invoice->items()->delete(); // Remove old items
    //         }

    //         // 3. Insert new items and deduct stock
    //         foreach ($this->invoiceItems as $item) {
    //             $invoiceItem = InvoiceItem::create([
    //                 'invoice_id' => $invoice->id,
    //                 'product_id' => $item['product_id'],
    //                 'quantity' => $item['quantity'],
    //                 'price' => $item['price'],
    //                 'total' => $item['total'],
    //                 'discount_type' => $item['discount_type'] ?? 'fixed',
    //                 'discount' => $item['discount_type'] == 'percentage' ? $item['discount'] : 0,
    //                 'discount_amount' => $item['discount_amount'] ?? 0,
    //                 'tax_percentage' => floatval($item['tax_percentage'] ?? 0),
    //                 'tax_amount' => $item['tax_amount'] ?? 0,
    //                 'price_after_tax' => $item['price_after_tax'] ?? 0,
    //                 'net_total' => $item['net_total'],
    //             ]);

    //             if ($this->invoiceType == 'locked') {
    //                 $product = Product::find($item['product_id']);
    //                 $product->stockOut($item['quantity'], $invoice, 'Invoice deduction');
    //             }
    //         }

    //         DB::commit();            
    //         $this->invoice = $invoice;
    //         $invoice->load('customer', 'items.product.unit');
    //         if($this->invoiceType == 'draft')
    //         {
    //             return redirect()->route('invoices.index')->with('success', 'Invoice created successfully as a draft.');
    //         }
    //         elseif($this->invoiceType == 'quotation')
    //         {
    //             return redirect()->route('invoices.index')->with('success', 'Invoice created successfully as a quotation.');
    //         }
    //         elseif($this->invoiceType == 'locked')
    //         {
    //             $this->currentStep = 3;
    //         }
            
    //     } catch (\Exception $e) {
    //         dd($e);
    //         DB::rollBack();
    //         $this->addError('general', 'Failed to create invoice: ' . $e->getMessage());
    //     }
    // }

    public function createInvoice()
    {
        // $this->validate();
        DB::beginTransaction();
        try {
            // Store original invoice for comparison if we're updating
            $originalInvoice = null;
            if ($this->invoiceId) {
                $originalInvoice = Invoice::with('items')->find($this->invoiceId);
            }
            
            // Create or update invoice
            $invoice = Invoice::updateOrCreate(
                ['id' => $this->invoiceId ?? null],
                [
                    'customer_id' => $this->customer_id,
                    'total_amount' => $this->total_amount,
                    'discount_type' => $this->discount_type,
                    'discount' => $this->discount,
                    'tax_amount' => $this->tax_amount,
                    'subtotal' => $this->subtotal,
                    'due_amount' => $this->due_amount,
                    'invoice_date' => $this->invoice_date,
                    'type' => $this->invoiceType,
                    'is_locked' => $this->invoiceType == 'locked' ? true : false,
                    'notes' => $this->notes,
                    'payment_method' => $this->payment_method,
                    'seller_id' => Auth::id(),
                ]
            );

            // Clear existing taxables if updating
            $invoice->taxables()->delete();

            // Store new taxables
            foreach ($this->taxablesData as $data) {
                $invoice->taxables()->create($data);
            }
            if($this->orderId)
            {
                OrderRequest::where('id', $this->orderId)->update(['converted_invoice_id' => $invoice->id, 'status' => 'approved']);
            }
            // Process stock adjustments only for locked invoices
            if ($this->invoiceType == 'locked') {
                // dd($this->invoiceItems);
                // If editing an existing locked invoice, handle stock adjustment differences
                if ($originalInvoice && $originalInvoice->type == 'locked') {
                    // Create a map of original items for easy comparison
                    $originalItems = [];
                    foreach ($originalInvoice->items as $item) {
                        $originalItems[$item->product_id] = $item;
                    }
                    // dd($originalItems, 444);
                    // Delete old items - we'll recreate them all
                    $invoice->items()->delete();
                    
                    // Process each new item
                    foreach ($this->invoiceItems as $item) {
                        // Create the new invoice item
                        $invoiceItem = InvoiceItem::create([
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
                            'price_after_tax' => $item['price_after_tax'] ?? 0,
                            'net_total' => $item['net_total'],
                        ]);
                        
                        $product = Product::find($item['product_id']);
                        
                        // Check if this product was in the original invoice
                        if (isset($originalItems[$item['product_id']])) {
                            $originalQty = $originalItems[$item['product_id']]->quantity;
                            $newQty = $item['quantity'];
                            // Calculate the difference in quantity
                            $qtyDifference = $newQty - $originalQty;
                            // Only adjust stock if there's a difference
                            if ($qtyDifference != 0) {
                                if ($qtyDifference > 0) {
                                    // Additional items sold - need to decrease stock
                                    $product->stockOut($qtyDifference, $invoice, 'Invoice update - additional items');
                                } else {
                                    // Fewer items sold - need to increase stock
                                    $product->stockIn(abs($qtyDifference), $invoice, 'Invoice update - returned items');
                                }
                            }
                            
                            // Remove from original items array to track what was processed
                            unset($originalItems[$item['product_id']]);
                        } else {
                            // This is a new product added to the invoice
                            $product->stockOut($item['quantity'], $invoice, 'Invoice update - new product added');
                        }
                    }
                    
                    // Any items left in originalItems array were removed from the invoice
                    foreach ($originalItems as $productId => $item) {
                        $product = Product::find($productId);
                        // Return stock for products no longer on the invoice
                        $product->stockIn($item->quantity, $invoice, 'Invoice update - product removed');
                    }
                } else {
                    // New locked invoice - simply deduct stock for all items
                    foreach ($this->invoiceItems as $item) {
                        // Create the invoice item
                        $invoiceItem = InvoiceItem::create([
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
                            'price_after_tax' => $item['price_after_tax'] ?? 0,
                            'net_total' => $item['net_total'],
                        ]);
                        
                        // Deduct stock
                        $product = Product::find($item['product_id']);
                        $product->stockOut($item['quantity'], $invoice, 'Invoice creation - item sold');
                    }

                }
            } else {
                // For non-locked invoices (draft or quotation), just create items without stock adjustments
                foreach ($this->invoiceItems as $item) {
                    $invoiceItem = InvoiceItem::create([
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
                        'price_after_tax' => $item['price_after_tax'] ?? 0,
                        'net_total' => $item['net_total'],
                    ]);
                }
            }

            DB::commit();            
            $this->invoice = $invoice;
            $invoice->load('customer', 'items.product.unit');
            
            if ($this->invoiceType == 'draft') {
                return redirect()->route('invoices.index')->with('success', 'Invoice created successfully as a draft.');
            } elseif ($this->invoiceType == 'quotation') {
                return redirect()->route('invoices.index')->with('success', 'Invoice created successfully as a quotation.');
            } elseif ($this->invoiceType == 'locked') {
                $this->currentStep = 3;
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Failed to create invoice: ' . $e->getMessage());
            // Log the error for debugging
            \Log::error('Invoice creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
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
        $this->addItem();
        $this->currentStep = 1;
    }

}