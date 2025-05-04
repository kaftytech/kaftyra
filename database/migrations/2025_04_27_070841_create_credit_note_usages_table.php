<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_note_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_note_id')->constrained('credit_notes')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->enum('usage_type',['debit','credit'])->default('debit'); // debit, credit
            $table->decimal('used_amount', 10, 2)->default(0); // amount used from the credit note
            $table->decimal('opening_balance', 10, 2)->default(0); // opening balance of the credit note
            $table->decimal('closing_balance', 10, 2)->default(0); // closing balance of the credit note
            $table->string('note')->nullable();
            $table->foreignId('applied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_usages');
    }
};
