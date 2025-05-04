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
        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_note_id')->constrained('credit_notes')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // original price at the time of credit
            $table->decimal('total', 10, 2); // quantity × price
            $table->enum('discount_type', ['percentage', 'fixed','free']);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('price_after_tax', 10, 2); // price + tax
            $table->decimal('net_total', 10, 2); // total - discount + tax
            $table->text('reason')->nullable(); // Reason for returning this item
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_items');
    }
};
