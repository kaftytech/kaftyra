<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class Vendors extends Model
{
    use HasAuditLog;
    protected $table = 'vendors';

    protected $guarded = [];

    public function products()
{
    return $this->belongsToMany(Product::class, 'product_vendors')
                ->withPivot('vendor_price')
                ->withTimestamps();
}

}
