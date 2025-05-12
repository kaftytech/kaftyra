<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    protected $guarded = [];

    public function bill()
    {
        return $this->belongsTo(PurchaseBill::class, 'bill_id');
    }

}
