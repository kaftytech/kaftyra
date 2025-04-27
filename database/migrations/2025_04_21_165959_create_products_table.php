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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_code')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('barcode')->nullable();
            $table->string('image')->nullable();
            $table->decimal('mrp', 10, 2)->nullable();     
            $table->decimal('selling_price', 10, 2)->nullable();     
            $table->string('gst_percentage')->nullable();
            $table->string('hsn_code')->nullable(); 
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
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
        Schema::dropIfExists('products');
    }
};
