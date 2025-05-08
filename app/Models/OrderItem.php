<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class OrderItem extends Model
{
    use HasAuditLog;
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
