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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->enum('discount_type', ['percentage', 'fixed','free']);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0); // discount on the item
            $table->string('tax_type')->nullable(); // e.g., VAT, GST
            $table->string('tax_percentage')->nullable(); 
            $table->decimal('tax_amount', 10, 2)->default(0); // tax on the item            
            $table->decimal('subtotal', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->date('invoice_date');
            $table->enum('type', ['draft', 'quotation', 'locked'])->default('draft'); // sale, purchase, service
            $table->enum('status', ['unpaid', 'partial', 'paid','cancelled'])->default('unpaid'); // unpaid, partial, paid
            $table->boolean('is_locked')->default(false); // true if the invoice is locked
            $table->text('notes')->nullable();
            $table->string('currency')->default('USD');
            $table->string('payment_method')->nullable(); // e.g., credit card, bank transfer
            $table->string('transaction_id')->nullable(); // for tracking payments
            $table->unsignedBigInteger('seller_id')->nullable(); // user who created the invoice
            $table->unsignedBigInteger('order_id')->nullable(); // user who created the invoice
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
