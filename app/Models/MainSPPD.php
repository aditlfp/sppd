<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainSPPD extends Model
{
    /** @use HasFactory<\Database\Factories\MainSPPDFactory> */
    use HasFactory;
    protected $fillable = [
        'auth_official',
        'user_id',
        'eslon_id',
        'maksud_perjalanan',
        'alat_angkutan',
        'tempat_berangkat',
        'maps_berangkat',
        'tempat_tujuan',
        'maps_tujuan',
        'lama_perjalanan',
        'date_time_berangkat',
        'date_time_kembali',
        'nama_pengikut',
        'jabatan_pengikut',
        'uang_saku',
        'e_toll',
        'makan',
        'lain_lain_desc',
        'lain_lain',
        'date_time_arrive',
        'arrive_at',
        'foto_arrive',
        'continue',
        'date_time_destination',
        'foto_destination',
        'nama_diperintah',
        'date_time',
        'verify',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eslon()
    {
        return $this->belongsTo(Eslon::class);
    }

}
