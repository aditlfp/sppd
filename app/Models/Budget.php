<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory;

    protected $fillable = [
        'pocket_money_id',
        'bbm',
        'e_toll',
        'makan',
        'laian_lain'
    ];

    public function PocketMoney()
    {
        return $this->belongsTo(PocketMoney::class);
    }
}
