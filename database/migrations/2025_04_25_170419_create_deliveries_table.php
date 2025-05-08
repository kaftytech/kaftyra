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

            $table->enum('delivery_type', ['vehicle', 'courier'])->default('courier');
        
            $table->unsignedBigInteger('vehicle_id')->nullable(); // Only used if type = vehicle
        
            $table->date('delivered_date')->nullable();
        
            $table->enum('status', ['pending','packed', 'shipped' ,'delivered', 'canceled'])->default('pending');
        
            $table->string('tracking_number')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('courier_number')->nullable();
        
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
