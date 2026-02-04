<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
