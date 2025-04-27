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
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('return_type',['manual','invoice','customer'])->default('manual'); // manual, invoice, customer
            $table->date('return_date');
            $table->string('total_quantity')->nullable(); // total quantity of items returned
            $table->decimal('total_amount', 10, 2); // sum of all return items
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // status of the return
            $table->text('reason')->nullable(); // overall reason
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
        Schema::dropIfExists('product_returns');
    }
};
