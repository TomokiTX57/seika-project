<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeroSystemDetail extends Model
{
    protected $fillable = [
        'zero_system_header_id',
        'initial_chips',
    ];

    public function header()
    {
        return $this->belongsTo(ZeroSystemHeader::class, 'zero_system_header_id');
    }
}
