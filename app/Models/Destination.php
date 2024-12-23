<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    /** @use HasFactory<\Database\Factories\DestinationFactory> */
    use HasFactory;

    protected $fillable = [
        'arrive_id',
        'continue',
        'date_time',
        'foto'
    ];

    public function Arrive()
    {
        return $this->belongsTo(Arrive::class);
    }
}
