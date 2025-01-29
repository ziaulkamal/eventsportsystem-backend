<?php
// app/Models/Athlete.php
namespace App\Models;


use App\Models\Kemendagri\Regencies;
use App\Models\Person;
use App\Models\Sport;
use App\Models\SportClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    use HasFactory;

    protected $table = 'athletes'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'id',
        'peopleId',
        'sportClassId',
        'sportId',
        'height',
        'weight',
        'achievements',
        'regionalRepresentative',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
    ];

    // Relationships
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
