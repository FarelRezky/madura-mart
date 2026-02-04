<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ⚠️ CRITICAL: 'picture' MUST be in this list. Check this file first! ⚠️
    protected $fillable = [
        'serial_number',
        'name',
        'type',
        'expiration_date',
        'price',
        'stock',
        'picture' 
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
}