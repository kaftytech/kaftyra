<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class OrderRequest extends Model
{
    use HasAuditLog;
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

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($orderRequest) {
            $setting = PrefixSetting::where('prefix_for', 'OrderRequest')->first();
    
            $number = str_pad($setting->current_number, 5, '0', STR_PAD_LEFT);
    
            $suffix = $setting->suffix ?? '';
            $parts = [];

            if ($setting->prefix) {
                $parts[] = $setting->prefix;
            }

            if ($suffix) {
                $parts[] = $suffix;
            }

            if ($number) {
                $parts[] = $number;
            }

            $orderRequest->order_id = implode('-', $parts);
    
            // Increment the current number
            $setting->increment('current_number');
        });
    }
}
