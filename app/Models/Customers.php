<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers'; // Specify the table name if it's different from the default
    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'zip_code',
        'vat_number',
        'gst_number',
        'bank_account_number',
        'bank_name',
        'bank_branch',
        'bank_ifsc_code',
        'bank_swift_code',
        'bank_account_holder_name',
        'bank_account_type',
        'payment_terms',
        'payment_method',
        'currency',
        'status',
        'type',
        'rating',
        'category',
        'tags',
        'website_url',
        'social_media_links',
        'logo',
        'profile_picture',
        'contact_person',
        'contact_email',
        'contact_phone',
        'contact_mobile',
        'contact_address',
        'notes',
        'lead_id',
        'created_by',
        'updated_by',
        // Add other fields as needed
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
