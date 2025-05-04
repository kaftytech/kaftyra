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
        Schema::create('prefix_settings', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_for'); // model class name like App\Models\Invoice
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->unsignedBigInteger('start_number')->default(1);
            $table->unsignedBigInteger('current_number')->default(1); // For tracking
            $table->boolean('auto_increment')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefix_settings');
    }
};
