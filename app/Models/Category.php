<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; // Specify the table name if it's different from the default
    protected $fillable = [
        'name',
        'description',
        // Add other fields as needed
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
