<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasUuids;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'peopleId',
        'username',
        'email',
        'password',
        'levelId',
        'status',
        'activationNumber',
        'lastLogin',
    ];

    protected $hidden = [
        'password',
        'activationNumber',
    ];

    protected $casts = [
        'id' => 'string',
        'lastLogin' => 'datetime',
        'password' => 'hashed'
    ];

    // Relasi ke tabel people
    public function people()
    {
        return $this->belongsTo(Person::class, 'peopleId');
    }

    // Relasi ke tabel levels
    public function level()
    {
        return $this->belongsTo(Level::class, 'levelId');
    }
}
