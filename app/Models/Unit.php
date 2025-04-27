<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units'; // Specify the table name if it's different from the default
    protected $fillable = [
        'name',
        'symbol',
        // Add other fields as needed
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
