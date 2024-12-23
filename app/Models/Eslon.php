<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eslon extends Model
{
    /** @use HasFactory<\Database\Factories\EslonFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'jabatan_id'
    ];

    public function Jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
