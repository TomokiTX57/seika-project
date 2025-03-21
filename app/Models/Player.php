<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',  // 追加
        'player_my_id',
        'is_subscribed',
        'uid',
    ];

    public function tournamentTransactions()
    {
        return $this->hasMany(TournamentTransaction::class);
        return $this->hasMany(\App\Models\TournamentTransaction::class);
    }

    public function ringTransactions()
    {
        return $this->hasMany(RingTransaction::class);
    }
}
