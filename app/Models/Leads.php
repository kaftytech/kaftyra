<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $table = 'leads'; // Specify the table name if it's different from the default
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'source'
        // Add other fields as needed
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
