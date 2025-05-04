<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function stockAdjustments()
    {
        return $this->morphMany(StockAdjustment::class, 'adjustable');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $setting = PrefixSetting::where('prefix_for', 'Invoice')->first();
    
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

            $invoice->invoice_number = implode('-', $parts);
    
            // Increment the current number
            $setting->increment('current_number');
        });
    }

    public function taxables()
    {
        return $this->morphMany(Taxable::class, 'taxable');
    }

}
