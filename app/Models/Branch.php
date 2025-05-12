<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class Branch extends Model
{
    use HasAuditLog;

    protected $guarded = [];

   public function users()
    {
        return $this->belongsToMany(User::class, 'branch_users', 'branch_id', 'user_id');
    }


}
