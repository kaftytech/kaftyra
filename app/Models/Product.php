<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'image',
        'description',
        'price',
        'category_id',
        'product_code',
        'mrp',
        'selling_price',
        'gst_percentage',
        'hsn_code',
        'vendor_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'unit_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function availableStock()
    {
        return $this->hasMany(Stock::class)->where('type', 'in')->sum('quantity');
    }
}
