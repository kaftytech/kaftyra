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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable'); // replaces reference_type + reference_id
            $table->date('date'); // Transaction date
            $table->string('description'); // Transaction description (e.g., "Invoice #123")
            $table->enum('type', ['credit', 'debit']); // Transaction type
            $table->decimal('amount', 15, 2); // Transaction amount
            $table->decimal('opening_balance', 15, 2); // Balance before the transaction
            $table->decimal('closing_balance', 15, 2); // Balance after the transaction
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
