<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class PurchaseOrder extends Model
{
    use HasAuditLog;

    protected $guarded = [];
    public function orderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($purchase) {
            $setting = PrefixSetting::where('prefix_for', 'Purchase')->first();
    
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

            $purchase->po_number = implode('-', $parts);
    
            // Increment the current number
            $setting->increment('current_number');
        });
    }

    public function taxables()
    {
        return $this->morphMany(Taxable::class, 'taxable');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendors::class);
    }

}
