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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., Office Rent, Internet Bill
            $table->decimal('amount', 10, 2);
            $table->text('note')->nullable();
            $table->date('expense_date');
            $table->string('payment_mode')->nullable(); // Cash, Bank, UPI etc.
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('transaction_id')->nullable(); // Transaction ID from payment gateway
            $table->string('reference_number')->nullable(); // Reference number for the payment
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
