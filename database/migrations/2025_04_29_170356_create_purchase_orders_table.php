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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('po_number')->unique(); // e.g., PO-2024-001
            $table->date('po_date');
            $table->enum('status', ['pending', 'delivered', 'rejected', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 15, 2);
            $table->enum('discount_type', ['percentage', 'fixed','free']);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0); // discount on the item
            $table->string('tax_type')->nullable(); // e.g., VAT, GST
            $table->string('tax_percentage')->nullable(); 
            $table->decimal('tax_amount', 10, 2)->default(0); // tax on the item            
            $table->decimal('subtotal', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->string('currency')->default('INR');
            $table->string('payment_method')->nullable(); // e.g., credit card, bank transfer
            $table->string('transaction_id')->nullable(); // for tracking payments
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
