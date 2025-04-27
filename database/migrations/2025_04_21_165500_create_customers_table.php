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
            $table->string('customer_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_swift_code')->nullable();
            $table->string('bank_account_holder_name')->nullable();
            $table->string('bank_account_type')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->default('active'); // Active, inactive, blocked
            $table->string('type')->default('customer'); // Customer, client, contractor
            $table->string('rating')->nullable(); // Rating of the customer
            $table->string('category')->nullable(); // Category of the customer
            $table->string('tags')->nullable(); // Tags for the customer
            $table->string('website_url')->nullable(); // Website URL of the customer
            $table->string('social_media_links')->nullable(); // Social media links of the customer
            $table->string('logo')->nullable(); // Logo of the customer
            $table->string('profile_picture')->nullable(); // Profile picture of the customer
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable(); // Contact email of the customer
            $table->string('contact_phone')->nullable(); // Contact phone of the customer
            $table->string('contact_mobile')->nullable(); // Contact mobile of the customer
            $table->string('contact_address')->nullable(); // Contact address of the customer
            $table->string('notes')->nullable(); // Notes about the customer
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete(); // Optional link to lead
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('customers');
    }
};
