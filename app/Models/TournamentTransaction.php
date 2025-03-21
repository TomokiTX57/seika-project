<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TournamentTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'player_id',
        'store_id',
        'chips',
        'points',
        'accounting_number',
        'comment',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
