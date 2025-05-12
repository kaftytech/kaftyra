<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseBill extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($purchaseBill) {
            $setting = PrefixSetting::where('prefix_for', 'PurchaseBill')->first();
    
            $digits = $setting->number_digits ?? 5;
            $number = str_pad($setting->current_number, $digits, '0', STR_PAD_LEFT);
    
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

            $purchaseBill->bill_number = implode('-', $parts);
    
            // Increment the current number
            $setting->increment('current_number');
        });
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(VendorPayment::class, 'bill_id');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    
}
