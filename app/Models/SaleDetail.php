<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_serial', 
        'selling_price', // <--- UBAH BAGIAN INI, SEBELUMNYA 'price'
        'qty',
        'subtotal'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_serial', 'serial_number');
    }
}