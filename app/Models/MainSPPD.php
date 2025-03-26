<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainSPPD extends Model
{
    /** @use HasFactory<\Database\Factories\MainSPPDFactory> */
    use HasFactory;
    protected $fillable = [
        'code_sppd',
        'auth_official',
        'user_id',
        'eslon_id',
        'maksud_perjalanan',
        'alat_angkutan',
        'nama_kendaraan_lain',
        'tempat_berangkat',
        'maps_berangkat',
        'tempat_tujuan',
        'lama_perjalanan',
        'date_time_berangkat',
        'date_time_kembali',
        'nama_pengikut',
        'jabatan_pengikut',
        'uang_saku',
        'e_toll',
        'makan',
        'verify',
        'note',
        'lain_lain_desc',
        'lain_lain'
    ];

    protected $casts = [
        'lain_lain_desc' => 'array',
        'lain_lain' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eslon()
    {
        return $this->belongsTo(Eslon::class);
    }

    public function transportation()
    {
        return $this->belongsTo(Transportation::class, 'alat_angkutan', 'id');
    }

}
