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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('designation');
            $table->string('department')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('dob')->nullable(); // Date of Birth
            $table->string('gender')->nullable(); // Male / Female / Other
            $table->string('emergency_contact')->nullable(); // Emergency phone
            $table->string('national_id')->nullable(); // Aadhaar, PAN, etc.
            $table->string('employee_code')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
