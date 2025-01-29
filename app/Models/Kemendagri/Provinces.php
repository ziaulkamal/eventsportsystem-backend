<?php

namespace App\Models\Kemendagri;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $table = 'reg_provinces';
    protected $fillable = [
        'id',
        'name',
    ];
}
