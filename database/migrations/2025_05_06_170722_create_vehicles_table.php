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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique(); // e.g., TN-45-AB-1234
            $table->string('type')->nullable();         // e.g., Truck, Van, Bike
            $table->string('driver_name')->nullable();  // Optional: if you want to show who’s driving
            $table->string('driver_contact')->nullable();
        
            $table->text('notes')->nullable();
        
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
