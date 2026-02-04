<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_number_purchase',
        'serial_number_product',
        'purchase_price',
        'selling_margin',
        'purchase_amount',
        'subtotal',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'serial_number_product', 'serial_number');
    }
}