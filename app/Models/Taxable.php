<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxable extends Model
{
    protected $guarded = [];
    public function taxable()
    {
        return $this->morphTo();
    }

    public function taxSetting()
    {
        return $this->belongsTo(TaxSetting::class);
    }
}
