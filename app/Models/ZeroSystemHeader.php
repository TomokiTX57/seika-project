<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeroSystemHeader extends Model
{
    use HasFactory;

    protected $fillable = ['player_id', 'store_id', 'ring_transaction_id', 'final_chips'];

    public function details()
    {
        return $this->hasMany(ZeroSystemDetail::class, 'zero_system_header_id');
    }
}
