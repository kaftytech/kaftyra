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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_registration_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();

            $table->string('vat_number')->nullable();
            $table->string('tax_number')->nullable();

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
            $table->string('type')->default('supplier'); // Supplier, vendor, contractor
            $table->string('rating')->nullable(); // Rating of the vendor
            $table->string('category')->nullable(); // Category of the vendor
            $table->string('tags')->nullable(); // Tags for the vendor
            $table->string('website_url')->nullable(); // Website URL of the vendor
            $table->string('social_media_links')->nullable(); // Social media links of the vendor
            $table->string('logo')->nullable(); // Logo of the vendor
            $table->string('profile_picture')->nullable(); // Profile picture of the vendor
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable(); // Contact email of the vendor
            $table->string('contact_phone')->nullable(); // Contact phone of the vendor
            $table->string('contact_mobile')->nullable(); // Contact mobile of the vendor
            $table->string('contact_address')->nullable(); // Contact address of the vendor
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete(); // Optional link to lead
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
        Schema::dropIfExists('vendors');
    }
};
