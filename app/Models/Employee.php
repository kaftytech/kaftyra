<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PrefixSetting;
use App\Traits\HasAuditLog;

class Employee extends Model
{
    use HasAuditLog;
    protected $guarded = [];

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($employee) {
            $setting = PrefixSetting::where('prefix_for', 'Employee')->first();
    
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

            $employee->employee_code = implode('-', $parts);
    
            // Increment the current number
            $setting->increment('current_number');
        });
    }
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditlogable');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
