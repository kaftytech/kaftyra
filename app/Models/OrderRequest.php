<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    protected $fillable = [
        'customer_id', 'order_id' ,'request_date', 'status', 'approved_by', 'approved_date', 'notes'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
