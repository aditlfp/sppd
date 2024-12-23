<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrive extends Model
{
    /** @use HasFactory<\Database\Factories\ArriveFactory> */
    use HasFactory;

    protected $fillable = [
        "date_time",
        "arrive_at",
        "foto"
    ];
}
