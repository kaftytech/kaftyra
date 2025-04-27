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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Company Name
            $table->string('email')->nullable(); // e.g., Company Email
            $table->string('phone')->nullable(); // e.g., Company Phone
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('city')->nullable(); // e.g., City
            $table->string('state')->nullable(); // e.g., State
            $table->string('postal_code')->nullable(); // e.g., Postal Code
            $table->string('country')->nullable(); // e.g., Country
            $table->string('tax_number')->nullable(); // e.g., Tax Number
            $table->string('vat_number')->nullable(); // e.g., VAT Number
            $table->string('registration_number')->nullable(); // e.g., Registration Number
            $table->string('contact_person')->nullable(); // e.g., Contact Person
            $table->string('contact_person_email')->nullable(); // e.g., Contact Person Email
            $table->string('contact_person_mobile')->nullable(); // e.g., Contact Person Mobile
            $table->string('website')->nullable(); // e.g., Company Website
            $table->string('logo')->nullable(); // e.g., Company logo
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
        Schema::dropIfExists('companies');
    }
};
