<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $guarded = ['id'];

    public function returnItems()
    {
        return $this->hasMany(ProductReturnItems::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }

}
