<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class Delivery extends Model
{
    protected $guarded = [];
    use HasAuditLog;

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
