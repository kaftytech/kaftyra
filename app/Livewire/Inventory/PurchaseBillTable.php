<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\PurchaseBill;
use App\Models\VendorPayment;
use App\Models\Transaction;
use DB;

class PurchaseBillTable extends Component
{
    public $showPaymentModal = false;
    public $selectedPurchaseBill;
    public $paymentData = [
        'payment_date' => '',
        'notes' => '',
        'amount' => '',
        'reference_number' => '',
        'transaction_id' => '',
    ];
    public $paymentDetails = [];
    public $errorMessage = '';

    public function render()
    {
        $purchase_bills = PurchaseBill::paginate(10);

        return view('livewire.inventory.purchase-bill-table', compact('purchase_bills'));
    }

    public function payment($billId)
    {
        $purchaseBill = PurchaseBill::findOrFail($billId);

        $this->selectedPurchaseBill = $purchaseBill;
        $this->paymentData = [
            'payment_date' => now()->format('Y-m-d'),
            'notes' => '',
            'amount' => $purchaseBill->due_amount ?? $purchaseBill->total,
            'reference_number' => '',
            'transaction_id' => '',
        ];
        $this->paymentDetails = $purchaseBill->payments()->select('amount', 'payment_date', 'reference_number','transaction_id', 'notes')->get();
        $this->showPaymentModal = true;
    }

    public function storePayment()
    {
        DB::beginTransaction();
    
        try {
            $bill = $this->selectedPurchaseBill;
            $data = $this->paymentData;
            
            if($data['amount'] > $bill->due_amount) {
                $this->errorMessage = 'Payment amount cannot be greater than due amount';
                return;
            }
            $payment = $bill->payments()->create([
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'payment_mode' => 'cash', // or dynamic if you want
                'transaction_id' => $data['transaction_id'],
                'reference_number' => $data['reference_number'],
                'notes' => $data['notes'],
                'created_by' => auth()->id(),
            ]);
        
            $bill->transaction()->create([
                'date' => $data['payment_date'],
                'description' => 'Payment for Bill #' . $bill->bill_number,
                'type' => 'debit',
                'amount' => $data['amount'],
                'opening_balance' => 0,
                'closing_balance' => 0,
            ]);
    
            $bill->update([
                'paid_amount' => $bill->paid_amount + $data['amount'],
                'due_amount' => $bill->due_amount - $data['amount'],
            ]);

            // Calculate invoice status
            if ($bill->paid_amount == $bill->total_amount) {
                $status = 'paid';
            } elseif ($bill->paid_amount > 0 && $bill->paid_amount < $bill->total_amount) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            // Update the invoice status
            $bill->update([
                'status' => $status,
            ]);
            // Commit the transaction
            DB::commit();
    
            // Reset the form and show success message
            $this->reset(['showPaymentModal', 'selectedPurchaseBill', 'paymentData']);
            return redirect()->route('purchase-bills.index')->with('success', 'Payment successful.');
    
        } catch (\Exception $e) {
            dd($e);
            // Rollback the transaction if anything goes wrong
            DB::rollBack();
    
            // Log the error or display the message
            session()->flash('error', 'Payment failed. Please try again.');
            \Log::error('Payment error: ' . $e->getMessage());
        }
    }
}
