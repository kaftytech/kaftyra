<?php

namespace App\Livewire\Asset;

use Livewire\Component;
use App\Models\Expense;

class ExpenseManager extends Component
{
    public $expenses;
    public $title, $amount, $note, $expense_date, $payment_mode, $status = 'pending', $transaction_id, $reference_number;
    public $expenseId = null;
    public $showForm = false;

    public function render()
    {
        $this->expenses = Expense::latest()->get();
        return view('livewire.asset.expense-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->showForm = true;
        $this->expense_date = now()->format('Y-m-d');
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->fill($expense->only([
            'title', 'amount', 'note', 'expense_date', 'payment_mode', 'status',
            'transaction_id', 'reference_number'
        ]));
        $this->expenseId = $expense->id;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::updateOrCreate(
            ['id' => $this->expenseId],
            [
                'title' => $this->title,
                'amount' => $this->amount,
                'note' => $this->note,
                'expense_date' => $this->expense_date,
                'payment_mode' => $this->payment_mode,
                'status' => $this->status,
                'transaction_id' => $this->transaction_id,
                'reference_number' => $this->reference_number,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]
        );
        
        // Create/update transaction
        $expense->transaction()->updateOrCreate([], [
            'date' => $this->expense_date,
            'description' => $this->title,
            'type' => 'debit',
            'amount' => $this->amount,
            'opening_balance' => 0, // Optional: calculate from account
            'closing_balance' => 0, // Optional: calculate from account
        ]);
        
        $this->resetInputFields();
        $this->showForm = false;
    }

    public function delete($id)
    {
        Expense::findOrFail($id)->delete();
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->amount = '';
        $this->note = '';
        $this->expense_date = '';
        $this->payment_mode = '';
        $this->status = 'pending';
        $this->transaction_id = '';
        $this->reference_number = '';
        $this->expenseId = null;
    }
}
