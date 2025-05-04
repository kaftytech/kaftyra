<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'vendor_id',
        'date',
        'quantity',
        'type',
        'stock_type',
        'reference_id',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendors::class);
    }

    // In Stock Model (Stock.php)
    public static function changeStock($productId, $quantity, $type, $referenceId)
    {
        // Create a new stock record to track the movement
        return self::create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'type' => $type,  // e.g., 'in' or 'out' depending on the operation
            'stock_type' => 'sale',  // You can use 'sale', 'purchase', etc., based on your use case
            'reference_id' => $referenceId,  // Reference to the invoice or order
            'date' => now(),
            'note' => 'Stock movement for invoice #' . $referenceId, // Or any other relevant note
        ]);
    }


}
