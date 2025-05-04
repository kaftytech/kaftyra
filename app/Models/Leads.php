<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditLog;
use Illuminate\Notifications\Notifiable;
class Leads extends Model
{
    use HasAuditLog;
    use Notifiable;
    
    protected $table = 'leads'; // Specify the table name if it's different from the default
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditlogable');
    }
}
