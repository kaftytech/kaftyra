<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'signature_path',
    ];
}
