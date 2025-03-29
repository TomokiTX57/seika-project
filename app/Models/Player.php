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
        'comment',
        'is_subscribed',
        'uid',
    ];

    public function tournamentTransactions()
    {
        return $this->hasMany(TournamentTransaction::class);
    }

    public function ringTransactions()
    {
        return $this->hasMany(RingTransaction::class);
    }

    public function hasUnsettledZeroSystem(): bool
    {
        return \App\Models\ZeroSystemHeader::where('player_id', $this->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNull('final_chips')
            ->exists();
    }
}
