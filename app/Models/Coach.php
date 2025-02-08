<?php

namespace App\Models;

use App\Models\Kemendagri\Regencies;
use App\Models\Person;
use App\Models\Sport;
use App\Models\SportClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $table = 'coach'; // Set table name
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'peopleId',
        'role',
        'sportId',
        'regionalRepresentative',
    ];

    protected $casts = [
        'id' => 'string',
        'peopleId' => 'string',
        'sportId' => 'string',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'peopleId');
    }

    public function sportClass()
    {
        return $this->belongsTo(SportClass::class, 'sportClassId');
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sportId');
    }

    // Relasi ke model Provinces
    public function regional()
    {
        return $this->belongsTo(Regencies::class, 'regionalRepresentative', 'id');
    }
}
