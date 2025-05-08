<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;

class Settings extends Model
{
    use HasAuditLog;
    protected $table = 'settings';

    protected $guarded = [];
}
