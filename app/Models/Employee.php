<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PrefixSetting;

class Employee extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($employee) {
            $setting = PrefixSetting::where('prefix_for', 'Employee')->first();
    
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

            $employee->employee_code = implode('-', $parts);
    
            // Increment the current number
            $setting->increment('current_number');
        });
    }
}
