<?php

namespace App\Models\Kemendagri;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Villages extends Model
{
    protected $table = 'reg_villages';
    protected $fillable = [
        'id',
        'district_id',
        'name',
    ];
}
