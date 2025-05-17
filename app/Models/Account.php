<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Account extends Model
{
    protected $guarded = [];
    public function accountable(){
        return $this->morphTo();
    }

    public function user()
    {
        return $this->morphTo('accountable');
    }
}
