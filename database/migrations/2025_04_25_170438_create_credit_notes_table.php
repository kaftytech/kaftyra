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
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->enum('credit_note_type',['manual','from_return'])->default('manual'); // manual, from_return
            $table->date('credit_note_date');
            $table->decimal('total_amount', 10, 2); // sum of all credit note items
            $table->decimal('used_amount', 10, 2)->default(0); // sum of all tax amounts
            $table->decimal('refund_amount', 10, 2)->default(0); // sum of all discount amounts
            $table->enum('status', ['draft', 'issued', 'used','partially_used' ,'refunded'])->default('draft'); // pending, approved, rejected
            $table->text('notes')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};
