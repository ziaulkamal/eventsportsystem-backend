<?php

namespace App\Models\Kemendagri;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    protected $table = 'reg_districts';

    protected $fillable = [
        'id',
        'regency_id',
        'name',
    ];

}
