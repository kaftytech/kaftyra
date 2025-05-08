<?php

namespace App\Livewire\Inventory\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendors;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockAdjustment;
use App\Models\TaxSetting;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesVendorSelection;
use App\Traits\HandlesProductSelection;
use DB;
class PurchaseOrderForm extends Component
{
    use HandlesVendorSelection, HandlesProductSelection;

    public $purchaseOrderId;
    public $purchaseOrder;
    public $selectedVendor;
    public $po_number;
    public $po_date;
    public $status = 'pending';
    public $notes;
    public $productSearch = '';

    public $purchaseOrderItems = [];
    public $vendors = [];
    public $availableProducts = [];

    // Invoice fields
    public $customer_id;
    public $invoice_date;
    public $discount_type = 'fixed';
    public $discount = 0;
    public $tax_type = 'percentage';
    public $tax_percentage = 0;
    public $payment_method = 'cash';
    // Calculated fields
    public $subtotal = 0;
    public $tax_amount = 0;
    public $total_amount = 0;
    public $due_amount = 0;

    public $taxes = []; // All active tax settings
    public $selectedTaxes = []; // e.g., ['CGST' => true, 'SGST' => false]
    public $taxValues = []; // e.g., ['CGST' => 9.00, 'SGST' => 9.00]
    public $taxablesData = [];
    public $currency = 'INR';
    public $paid_amount = 0;


    public function mount($purchaseOrderId = null)
    {   
        if ($purchaseOrderId) {
            $purchaseOrder = PurchaseOrder::with('orderItems.product')->findOrFail($purchaseOrderId);
            $this->loadPurchaseOrder($purchaseOrderId);

            $this->total_amount = $this->purchaseOrder->total_amount;
            $this->due_amount = $this->purchaseOrder->due_amount;
            $this->search_by_query = $this->purchaseOrder->vendor->company_name . ' | ' . $this->purchaseOrder->vendor->phone;
            $this->search_by_query_status = 'selected';

            $this->taxes = TaxSetting::where('is_active', true)->get();
            foreach ($this->taxes as $tax) {
                $matched = $purchaseOrder->taxables->firstWhere('tax_name', $tax->name);
                $this->selectedTaxes[$tax->name] = $matched ? true : false;
                $this->taxValues[$tax->name] = $matched ? $matched->rate : $tax->rate; // use stored rate or default
            }
        } else {
            $this->po_date = today()->format('Y-m-d');
            $this->taxes = TaxSetting::where('is_active', true)->get();

            foreach ($this->taxes as $tax) {
                $this->selectedTaxes[$tax->name] = false; // initially unselected
                $this->taxValues[$tax->name] = $tax->rate; // default from DB
            }
            $this->addItem();
        }
        $this->vendors = Vendors::all();
        $this->availableProducts = Product::with('unit')->get();

    }
    public function loadPurchaseOrder($purchaseOrderId)
    {
        $this->purchaseOrder = PurchaseOrder::with('orderItems.product')->findOrFail($purchaseOrderId);
        // dd($this->purchaseOrder);
        $this->purchaseOrderId = $this->purchaseOrder->id;
        $this->po_number = $this->purchaseOrder->po_number;
        $this->po_date = $this->purchaseOrder->po_date;
        $this->selectedVendor = $this->purchaseOrder->vendor_id;
        $this->status = $this->purchaseOrder->status;
        $this->customer_id = $this->purchaseOrder->customer_id;
        $this->invoice_date = $this->purchaseOrder->invoice_date;
        $this->discount_type = $this->purchaseOrder->discount_type;
        $this->discount = $this->purchaseOrder->discount;
        $this->tax_type = $this->purchaseOrder->tax_type;
        $this->tax_percentage = $this->purchaseOrder->tax_percentage;
        $this->notes = $this->purchaseOrder->notes;
        $this->payment_method = $this->purchaseOrder->payment_method;
        $this->currency = $this->purchaseOrder->currency;
        $this->paid_amount = $this->purchaseOrder->paid_amount;
        // Load invoice items
        $this->purchaseOrderItems = [];
        foreach ($this->purchaseOrder->orderItems as $item) {
            $this->purchaseOrderItems[] = [
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

    public function removeItem($index)
    {
        if (count($this->purchaseOrderItems) > 1) {
            unset($this->purchaseOrderItems[$index]);
            $this->purchaseOrderItems = array_values($this->purchaseOrderItems);
            $this->calculateInvoice();
        }
    }

    public function addItem()
    {
        $this->purchaseOrderItems[] = [
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
    public function handleProductSearchWrapper($index, $value, $vendorId = null)
    {
        $this->handleProductSearch($this->purchaseOrderItems, $index, $value, $vendorId);
    }


    public function selectProductWrapper($index, $productId)
    {
        $error = $this->selectVendorProduct(
            $this->purchaseOrderItems,
            $index,
            $productId,
            fn($i) => $this->calculateItemTotal($i),
            $this->selectedVendor
        );

        if ($error) {
            $this->addError("purchaseOrderItems.$index.product_id", $error);
        }
    }

    public function updatedpurchaseOrderItems($value, $index)
    {
        $parts = explode('.', $index);
    
        // Handle quantity, price, discount update
        if (count($parts) == 2 && in_array($parts[1], ['quantity', 'price', 'discount', 'tax_percentage'])) {
            $this->calculateItemTotal($parts[0]);
        }
    
        // Handle product search
        if (count($parts) == 2 && $parts[1] === 'product_search') {
            $this->handleProductSearchWrapper($parts[0], $value,$this->selectedVendor);
        }
    }
    public function highlightProductNextWrapper($index)
    {
        $this->highlightProductNext($this->purchaseOrderItems, $index);
    }

    public function highlightProductPreviousWrapper($index)
    {
        $this->highlightProductPrevious($this->purchaseOrderItems, $index);
    }

    public function selectHighlightedProductWrapper($index)
    {
        $this->selectHighlightedProduct($this->purchaseOrderItems, $index, fn($i, $id) => $this->selectProductWrapper($i, $id));
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
        $quantity = $this->purchaseOrderItems[$index]['quantity'];
        $price = $this->purchaseOrderItems[$index]['price'];
        $discountType = $this->purchaseOrderItems[$index]['discount_type'] ?? 'fixed';
        $discount = $this->purchaseOrderItems[$index]['discount'] ?? 0;
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
        $gstPercentage = !empty($this->purchaseOrderItems[$index]['tax_percentage']) ? 
            floatval($this->purchaseOrderItems[$index]['tax_percentage']) : 0;
            
        $afterDiscount = $total - $discountAmount;
        $tax = ($total * $gstPercentage) / 100;
        $priceAfterTax = $price + ($price * $gstPercentage) / 100;

        $this->purchaseOrderItems[$index]['total'] = $total;
        $this->purchaseOrderItems[$index]['discount_type'] = $discountType;
        $this->purchaseOrderItems[$index]['discount'] = $discountType == 'percentage' ? $discountPercentage : $discountAmount;
        $this->purchaseOrderItems[$index]['discount_amount'] = $discountAmount;
        $this->purchaseOrderItems[$index]['tax_percentage'] = $gstPercentage;
        $this->purchaseOrderItems[$index]['tax_amount'] = $tax;
        $this->purchaseOrderItems[$index]['price_after_tax'] = $priceAfterTax;
        $this->purchaseOrderItems[$index]['net_total'] = $afterDiscount + $tax;
        
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
        foreach ($this->purchaseOrderItems as $item) {
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

    public function submitOrder()
    {
         // $this->validate();
         DB::beginTransaction();
         try {
                $originalPurchase = null;
                if ($this->purchaseOrderId) {
                    $originalPurchase = PurchaseOrder::find($this->purchaseOrderId);
                }
             // Store original invoice for comparison if we're updating
             $originalItems = [];
             $isDelivered = $this->status === 'delivered';
            //  dd($isDelivered);
             if ($this->purchaseOrderId && $isDelivered) {
                 $originalItems = $originalPurchase->orderItems->keyBy('product_id');
             }            
             
             // Create or update invoice
             $purchaseOrder = PurchaseOrder::updateOrCreate(
                 ['id' => $this->purchaseOrderId ?? null],
                 [
                    'vendor_id' => $this->selectedVendor,
                    'total_amount' => $this->total_amount,
                    'discount_type' => $this->discount_type,
                    'discount' => $this->discount,
                    // 'tax_amount' => $this->tax_amount,
                    'subtotal' => $this->subtotal,
                    'due_amount' => $this->due_amount,
                    'po_date' => $this->po_date,
                    'notes' => $this->notes,
                    'status' => $this->status,
                    'payment_method' => $this->payment_method,
                    'branch_id' => 1,
                    'created_by' => Auth::id(),
                 ]
             );
             $purchaseOrder->taxables()->delete();

            // Store new taxables
            foreach ($this->taxablesData as $data) {
                $purchaseOrder->taxables()->create($data);
            }
             $purchaseOrder->orderItems()->delete();
             foreach ($this->purchaseOrderItems as $item) {
                $product = Product::find($item['product_id']);
            
                // If delivered, adjust stock
                if ($isDelivered) {
                    if (isset($originalItems[$item['product_id']])) {
                        $originalQty = $originalItems[$item['product_id']]->quantity;
                        $newQty = $item['quantity'];
                        $qtyDifference = $newQty - $originalQty;
            
                        if ($qtyDifference !== 0) {
                            if ($qtyDifference > 0) {
                                $product->stockIn($qtyDifference, $purchaseOrder, 'Purchase Order update - extra items received');
                            } else {
                                $product->stockOut(abs($qtyDifference), $purchaseOrder, 'Purchase Order update - quantity reduced');
                            }
                        }
            
                        unset($originalItems[$item['product_id']]);
                    } else {
                        $product->stockIn($item['quantity'], $purchaseOrder, 'Purchase Order update - new product received');
                    }
                }
            
                $purchaseOrder->orderItems()->create([
                    'purchase_order_id' => $purchaseOrder->id,
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
            
                // Any removed products - reverse stockIn (i.e., remove from stock)
                if ($isDelivered) {
                    foreach ($originalItems as $productId => $item) {
                        $product = Product::find($productId);
                        $product->stockOut($item->quantity, $purchaseOrder, 'Purchase Order update - item removed');
                    }
                }
            
             DB::commit();
             return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully.');
         } catch (\Exception $e) {
             DB::rollBack();
             throw $e;
         }

    }


    public function render()
    {
        return view('livewire.inventory.purchase-order.purchase-order-form');
    }
}
