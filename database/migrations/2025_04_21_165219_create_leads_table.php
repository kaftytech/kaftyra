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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable(); // e.g., Facebook, referral
            $table->date('follow_up_date')->nullable();
            $table->enum('status', ['new', 'contacted', 'converted', 'lost','hot_lead', 'warm_lead', 'cold_lead', 'not_interested'])->default('new'); // new, contacted, converted, lost
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('leads');
    }
};
