<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
class InvoiceTable extends Component
{
    public $showPaymentModal = false;
    public $selectedInvoice;
    public $paymentData = [
        'payment_date' => '',
        'notes' => '',
        'amount' => '',
        'reference_number' => '',
        'transaction_id' => '',
    ];
    public $paymentDetails = [];
    public $errorMessage = '';

    public function payment($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $this->selectedInvoice = $invoice;
        $this->paymentData = [
            'payment_date' => now()->format('Y-m-d'),
            'notes' => '',
            'amount' => $invoice->due_amount ?? $invoice->total,
            'reference_number' => '',
            'transaction_id' => '',
        ];
        $this->paymentDetails = $invoice->payments()->select('amount', 'payment_date', 'reference_number', 'transaction_id', 'notes')->get();
        $this->showPaymentModal = true;
    }

    public function storePayment()
    {
        DB::beginTransaction();
    
        try {
            $invoice = $this->selectedInvoice;
            $data = $this->paymentData;
            
            if($data['amount'] > $invoice->due_amount) {
                $this->errorMessage = 'Payment amount cannot be greater than due amount';
                return;
            }
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'payment_method' => 'cash', // or dynamic if you want
                'status' => 'completed',
                'transaction_id' => $data['transaction_id'],
                'reference_number' => $data['reference_number'],
                'notes' => $data['notes'],
                'created_by' => auth()->id(),
            ]);
    
            $lastTransaction = Transaction::latest()->first();
            $opening = $lastTransaction ? $lastTransaction->closing_balance : 0;
            $closing = $opening + $data['amount'];
    
            $invoice->transaction()->create([
                'date' => $data['payment_date'],
                'description' => 'Payment for Invoice #' . $invoice->id,
                'type' => 'credit',
                'amount' => $data['amount'],
                'opening_balance' => $opening,
                'closing_balance' => $closing,
            ]);
    
            $invoice->update([
                'paid_amount' => $invoice->paid_amount + $data['amount'],
                'due_amount' => $invoice->due_amount - $data['amount'],
            ]);

            // Calculate invoice status
            if ($invoice->paid_amount == $invoice->total_amount) {
                $status = 'paid';
            } elseif ($invoice->paid_amount > 0 && $invoice->paid_amount < $invoice->total_amount) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            // Update the invoice status
            $invoice->update([
                'status' => $status,
            ]);
            // Commit the transaction
            DB::commit();
    
            // Reset the form and show success message
            $this->reset(['showPaymentModal', 'selectedInvoice', 'paymentData']);
            return redirect()->route('invoices.index')->with('success', 'Payment successful.');
    
        } catch (\Exception $e) {
            dd($e);
            // Rollback the transaction if anything goes wrong
            DB::rollBack();
    
            // Log the error or display the message
            session()->flash('error', 'Payment failed. Please try again.');
            \Log::error('Payment error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $invoices = Invoice::paginate(10);
        return view('livewire.billing.invoice-table', [
            'invoices' => $invoices,
        ]);
    }
}
