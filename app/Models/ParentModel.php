<?php

namespace App\Models;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;
    protected $table = 'parents'; // Set table name

    protected $fillable = [
        'name',
    ];

    public function levels()
    {
        return $this->hasMany(Level::class, 'parentId');
    }
}
