<?php

namespace App\Imports;

use App\Models\Customers;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Customers([
            'customer_name'        => $row['customer_name'],
            'email'                => $row['email'],
            'phone'                => $row['phone'],
            'address_line_1'       => $row['address_line_1'],
            'address_line_2'       => $row['address_line_2'],
            'city'                 => $row['city'],
            'state'                => $row['state'],
            'country'              => $row['country'],
            'zip_code'             => $row['zip_code'],
            'vat_number'           => $row['vat_number'],
            'gst_number'           => $row['gst_number'],
            'bank_account_number'  => $row['bank_account_number'],
            'bank_ifsc_code'       => $row['bank_ifsc_code'],
        ]);
    }
    public function rules(): array
    {
        return [
            'customer_name'        => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'phone'                => 'required|string|max:20',
            'address_line_1'       => 'required|string|max:255',
            'address_line_2'       => 'nullable|string|max:255',
            'city'                 => 'required|string|max:100',
            'state'                => 'required|string|max:100',
            'country'              => 'required|string|max:100',
            'zip_code'             => 'required|string|max:20',
            'vat_number'           => 'nullable|string|max:50',
            'gst_number'           => 'nullable|string|max:50',
            'bank_account_number'  => 'nullable|string|max:50',
            'bank_ifsc_code'       => 'nullable|string|max:50',
        ];
    }
}
