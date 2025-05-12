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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // Personal Details
            $table->string('customer_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('profile_picture')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();

            // Company Details
            $table->string('company_name')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('website_url')->nullable();
            $table->string('logo')->nullable();
            $table->string('rating')->nullable();

            // Bank Details
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_account_holder_name')->nullable();
            $table->string('bank_account_type')->nullable();

            // Payment Details
            $table->string('payment_terms')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('currency')->nullable();

            // Misc
            $table->string('status')->default('active'); // Active, inactive, blocked
            $table->string('type')->default('customer'); // Customer, client, contractor
            $table->text('notes')->nullable();

            // Foreign Keys
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
