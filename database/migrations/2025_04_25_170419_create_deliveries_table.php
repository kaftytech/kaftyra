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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['pending','packed', 'shipped' ,'delivered', 'canceled'])->default('pending'); // pending, packed, shipped, delivered, canceled
            $table->string('tracking_number')->nullable(); // e.g., Tracking Number
            $table->string('courier_name')->nullable(); // e.g., Tracking URL
            $table->string('courier_number')->nullable(); // e.g., Tracking URL
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('delivered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
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
        Schema::dropIfExists('deliveries');
    }
};
