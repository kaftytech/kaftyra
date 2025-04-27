<?php

namespace App\Imports;

use App\Models\Vendors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VendorImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Vendors([
            'company_name' => $row['company_name'],
            'company_registration_number' => $row['company_registration_number'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'notes' => $row['notes'],
            'city' => $row['city'],
            'state' => $row['state'],
            'country' => $row['country'],
            'zip_code' => $row['zip_code'],
            'vat_number' => $row['vat_number'],
            'tax_number' => $row['tax_number'],
            'bank_account_number' => $row['bank_account_number'],
            'bank_ifsc_code' => $row['bank_ifsc_code'],
            'contact_person' => $row['contact_person'],
            'contact_email' => $row['contact_email'],
            'contact_phone' => $row['contact_phone'],
            'contact_mobile' => $row['contact_mobile'],
            'contact_address' => $row['contact_address'],
        ]);
    }
}
