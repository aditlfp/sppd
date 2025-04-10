<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPPDBellow extends Model
{
    /** @use HasFactory<\Database\Factories\SPPDBellowFactory> */
    use HasFactory;

    protected $fillable = [
        'code_sppd',
        'date_time_arrive',
        'arrive_at',
        'departed_at',
        'foto_arrive',
        'maps_tiba',
        'continue',
        'date_time_destination',
        'foto_destination',
        'maps_tujuan',
        'nama_diperintah',
        'date_time',
        'verify',
        'note'
    ];

    public function mainSppd()
    {
        return $this->belongsTo(MainSPPD::class, 'code_sppd', 'code_sppd');
    }
}
