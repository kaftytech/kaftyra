<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Billing\CreditNoteController;

use App\Http\Controllers\Billing\PaymentController;

Route::group(['prefix' => 'invoices'], function() {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post('/{invoice}/save-signature', [InvoiceController::class, 'saveSignature']);
    Route::get('/{invoice}/pdf', [InvoiceController::class, 'viewPDF'])->name('invoices.pdf.view');
    Route::get('/{invoice}/pdf/download', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf.download');
    Route::get('/convert/{order}', [InvoiceController::class, 'convertInvoice'])->name('convert.invoice');

});

Route::group(['prefix'=>'credit-notes'], function() {
    Route::get('/', [CreditNoteController::class, 'creditNoteIndex'])->name('credit-notes.index');
    Route::get('/create/{invoice}', [CreditNoteController::class, 'createCreditNote'])->name('credit-notes.create');
});

Route::group(['prefix' => 'payments'], function() {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
});

Route::get('/transactions', [PaymentController::class, 'transactionIndex'])->name('transactions.index');
