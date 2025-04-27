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
}
