<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PocketMoney extends Model
{
    /** @use HasFactory<\Database\Factories\PocketMoneyFactory> */
    use HasFactory;

    protected $fillable = [
        'anggaran',
        'eslon_id',
        'region_id'
    ];

    public function Eslon()
    {
        return $this->belongsTO(Eslon::class);
    }

    public function Region()
    {
        return $this->belongsTo(Region::class);
    }
}
