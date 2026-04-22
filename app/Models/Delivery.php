<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'deliveries';

    // Kolom yang boleh diisi (Mass Assignment)
    // SESUAIKAN dengan nama kolom di database kamu (resi_kode atau kode_resi)
    protected $fillable = [
        'resi_kode', 
        'kurir', 
        'status'
    ];
}