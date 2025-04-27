<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_request_id', 'product_id', 'quantity', 'notes'
    ];

    public function orderRequest()
    {
        return $this->belongsTo(OrderRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
