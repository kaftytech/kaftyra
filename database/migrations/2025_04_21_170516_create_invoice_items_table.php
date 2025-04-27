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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // selling price
            $table->decimal('total', 10, 2); // quantity × price
            $table->enum('discount_type', ['percentage', 'fixed','free']);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0); // discount on the item          
            $table->decimal('tax_percentage', 10, 2)->nullable(); 
            $table->decimal('tax_amount', 10, 2)->default(0); // tax on the item            
            $table->decimal('price_after_tax_and_discount', 10, 2); // price + tax
            $table->decimal('net_total', 10, 2); // total - discount + tax
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
