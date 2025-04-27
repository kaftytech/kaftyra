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
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->date('request_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('approved_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('converted_invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_requests');
    }
};
