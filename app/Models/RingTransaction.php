<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'store_id',
        'chips',
        'is_zero_system',
        'type',
        'action',
        'accounting_number',
        'comment',
    ];

    public function zeroSystemHeader()
    {
        return $this->hasOne(ZeroSystemHeader::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
