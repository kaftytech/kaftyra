<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $category = Category::firstOrCreate(
            ['name' => $row['category_name']],
            ['description' => $row['category_name']] // or any default description
        );
        
        $unit = Unit::firstOrCreate(
            ['name' => $row['unit_name']],
            ['symbol' => strtolower(substr($row['unit_name'], 0, 3))] // Example: "Kg" => "kg"
        );
        
        return new Product([
            'name'            => $row['name'],
            'product_code'    => $row['product_code'],
            'description'     => $row['description'],
            'barcode'         => $row['barcode'],
            'image'           => $row['image'],
            'mrp'             => $row['mrp'],
            'selling_price'   => $row['selling_price'],
            'gst_percentage'  => $row['gst_percentage'],
            'hsn_code'        => $row['hsn_code'],
            'category_id'     => $category->id,
            'unit_id'         => $unit->id,
        ]);
        
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string'],
            'product_code'   => ['nullable', 'string'],
            'description'    => ['nullable', 'string'],
            'barcode'        => ['nullable', 'string'],
            'mrp'            => ['nullable', 'numeric'],
            'selling_price'  => ['nullable', 'numeric'],
            'gst_percentage' => ['nullable', 'string'],
            'hsn_code'       => ['nullable', 'string'],
            'category_name'  => ['required', 'string'],
            'unit_name'      => ['required', 'string'],
        ];
    }
}
