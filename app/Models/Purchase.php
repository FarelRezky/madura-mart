<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_number',
        'purchase_date',
        'distributor_id',
        'total_price',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    // Relationship to Distributor
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    // Relationship to Purchase Details (THIS WAS MISSING)
    public function details()
    {
        // We link local 'note_number' to foreign 'note_number_purchase'
        return $this->hasMany(PurchaseDetail::class, 'note_number_purchase', 'note_number');
    }
}