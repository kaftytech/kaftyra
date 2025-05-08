<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class Vehicle extends Model
{
    use HasAuditLog;
    protected $fillable = [
        'vehicle_number',
        'type',
        'driver_name',
        'driver_contact',
        'notes',
        'branch_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
