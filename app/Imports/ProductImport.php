<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{

    public function model(array $row)
    {
        // Log the incoming row to check the data
        Log::debug('Importing row:', $row);
    
        $category = Category::firstOrCreate(
            ['name' => $row['category_name']],
            ['description' => $row['category_name']] 
        );
        
        $unit = Unit::firstOrCreate(
            ['name' => $row['unit_name']],
            ['symbol' => strtolower(substr($row['unit_name'], 0, 3))]
        );
    
        try {
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
        } catch (\Exception $e) {
            // Log the exception if an error occurs during product creation
            Log::error('Error importing product: ' . $e->getMessage());
            return null; // You can choose to skip the product or handle it differently
        }
    }
    
    public function rules(): array
    {
        return [
            'name'           => ['required', 'string'],
            'product_code'   => ['nullable', 'string'],
            'description'    => ['nullable', 'string'],
            'barcode'        => ['nullable', 'string'],
            // 'mrp'            => ['nullable', 'numeric'],
            // 'selling_price'  => ['nullable', 'numeric'],
            // 'gst_percentage' => ['nullable', 'string'],
            // 'hsn_code'       => ['nullable', 'string'],
            // 'category_name'  => ['required', 'string'],
            // 'unit_name'      => ['required', 'string'],
        ];
    }
}
