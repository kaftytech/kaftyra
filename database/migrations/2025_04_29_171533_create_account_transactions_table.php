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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->date('date'); // Transaction date
            $table->string('description'); // Transaction description (e.g., "Invoice #123")
            $table->enum('type', ['credit', 'debit']); // Transaction type
            $table->decimal('amount', 15, 2); // Transaction amount
            $table->decimal('opening_balance', 15, 2); // Balance before the transaction
            $table->decimal('closing_balance', 15, 2); // Balance after the transaction
            $table->string('txn_mode')->nullable();; // Transaction mode 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transactions');
    }
};
