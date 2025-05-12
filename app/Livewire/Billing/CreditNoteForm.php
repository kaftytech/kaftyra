<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Invoice;
use Carbon\Carbon;
use App\Models\TaxSetting;
class CreditNoteForm extends Component
{
    public $invoiceId, $creditNoteId;
    public $invoiceItems = [];
    public $subtotal = 0;
    public $tax_amount = 0;
    public $total_amount = 0;
    public $due_amount = 0;

        // Invoice fields
    public $invoice_number;
    public $customer_id;
    public $invoice_date;
    public $discount_type = 'fixed';
    public $discount = 0;
    public $discountAmount = 0;

    public $taxes = []; // All active tax settings
    public $selectedTaxes = []; // e.g., ['CGST' => true, 'SGST' => false]
    public $taxValues = []; // e.g., ['CGST' => 9.00, 'SGST' => 9.00]
    public $taxablesData = [];
    public $notes;
    public $payment_method;
    public $currency = 'INR';
    public $paid_amount = 0;
    public $creditNoteDate;
    public $invoice;
    public function mount($invoiceId = null, $creditNoteId = null)
    {
        $this->creditNoteDate = Carbon::now()->format('Y-m-d');
        if($invoiceId)
        {
            $this->loadInvoice($invoiceId);
        }
        
    }
    public function loadInvoice($invoiceId)
    {
        $this->invoice = Invoice::with(['taxables', 'items.product'])->find($invoiceId);
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
        $this->invoiceItems = []; // Initialize as an empty array
        $this->taxes = TaxSetting::where('is_active', true)->get();
            foreach ($this->taxes as $tax) {
                $matched = $this->invoice->taxables->firstWhere('tax_name', $tax->name);
                $this->selectedTaxes[$tax->name] = $matched ? true : false;
                $this->taxValues[$tax->name] = $matched ? $matched->rate : $tax->rate; // use stored rate or default
            }
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

    public function updatedInvoiceItems($value, $index)
    {
        $parts = explode('.', $index);
    
        // Handle quantity, price, discount update
        if (count($parts) == 2 && in_array($parts[1], ['quantity', 'price', 'discount','discount_amount'])) {
            $this->calculateItemTotal($parts[0]);
        }
    }

    public function calculateItemTotal($index)
    {
        $quantity = (int) ($this->invoiceItems[$index]['quantity'] ?? 0);
        $price = (float) ($this->invoiceItems[$index]['price'] ?? 0);
        $discountType = $this->invoiceItems[$index]['discount_type'] ?? 'fixed';
        $originalQuantit = $quantity;

        $total = $quantity * $price;
        $discountAmount = 0;
        $discountPerUnit = 0;
        $discountPercentage = 0;
        $afterDiscount = 0;

        if ($discountType === 'percentage') {
            $discountPercentage = floatval($this->invoiceItems[$index]['discount'] ?? 0);
            $discountPerUnit = ($price * $discountPercentage) / 100;
            $discountPricePerUnit = $price - $discountPerUnit;
            $afterDiscount = $discountPricePerUnit * $quantity;
            $discountAmount = $discountPerUnit * $quantity;
            
        } elseif ($discountType === 'fixed') {
            // Treat the stored discount as **discount per unit**
            $originalTotalDiscount = $this->invoiceItems[$index]['discount_amount'] ?? 0;
            $originalQuantity = (int) ($quantity);
            if ($originalQuantity > 0) {
                $discountPerUnit = $originalTotalDiscount / $originalQuantity;
            } else {
                $discountPerUnit = 0;
            }

            $discountPricePerUnit = $price - $discountPerUnit;
            $afterDiscount = $discountPricePerUnit * $quantity;
            $discountAmount = $discountPerUnit * $quantity;
        }

        // GST / tax calculation
        $gstPercentage = !empty($this->invoiceItems[$index]['tax_percentage']) 
            ? floatval($this->invoiceItems[$index]['tax_percentage']) 
            : 0;

        $tax = ($afterDiscount * $gstPercentage) / 100;
        $priceAfterTax = $price + ($price * $gstPercentage) / 100;

        // Set values
        $this->invoiceItems[$index]['total'] = $total;
        $this->invoiceItems[$index]['discount'] = $discountType === 'percentage' ? $discountPercentage : $discountPerUnit;
        $this->invoiceItems[$index]['discount_amount'] = $discountAmount;
        $this->invoiceItems[$index]['tax_percentage'] = $gstPercentage;
        $this->invoiceItems[$index]['tax_amount'] = $tax;
        $this->invoiceItems[$index]['price_after_tax'] = $priceAfterTax;
        $this->invoiceItems[$index]['net_total'] = $afterDiscount + $tax;

        $this->calculateInvoice();
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
    }

    public function render()
    {
        return view('livewire.billing.credit-note-form');
    }
}
