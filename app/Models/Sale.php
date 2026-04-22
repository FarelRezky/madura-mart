<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    protected $fillable = ['sale_number', 'sale_date', 'total_price'];
    public function details() { return $this->hasMany(SaleDetail::class); }
}