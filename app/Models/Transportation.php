<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    /** @use HasFactory<\Database\Factories\TransportationFactory> */
    use HasFactory;

    protected $fillable = [
        'jenis',
        'nama_kendaraan',
        'anggaran'
    ];
}
