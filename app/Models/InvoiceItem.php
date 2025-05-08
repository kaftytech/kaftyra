<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class InvoiceItem extends Model
{
    use HasAuditLog;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
