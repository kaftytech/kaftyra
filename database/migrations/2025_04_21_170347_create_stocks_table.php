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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date')->nullable();
            $table->integer('quantity'); // Positive for received, negative for removed
            $table->enum('type', ['in', 'out'])->default('in');
            $table->enum('stock_type', ['purchase', 'sale', 'adjustment', 'return', 'damage'])->default('purchase');
            $table->string('reference_id')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('stocks');
    }
};
