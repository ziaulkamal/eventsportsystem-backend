<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'hit_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
