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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->enum('discount_type', ['percentage', 'fixed','free']);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('price_after_tax', 15, 2);
            $table->decimal('net_total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
