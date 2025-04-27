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
        Schema::create('product_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_return_id')->constrained('product_returns')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // per unit price (without tax)
            $table->enum('discount_type', ['percentage', 'fixed','free']);
            $table->decimal('discount', 8, 2)->nullable();
            $table->string('tax_percentage')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0); // per unit tax
            $table->decimal('total', 10, 2); // (price + tax) × quantity
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_return_items');
    }
};
