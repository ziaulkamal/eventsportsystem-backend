<?php
// app/Models/PeopleHousing.php
namespace App\Models;

use App\Models\Housing;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleHousing extends Model
{
    use HasFactory;

    protected $table = 'people_housing'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'peopleId',
        'houseId',
        'responsibleId',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
    ];

    // Relationships
    public function person()
    {
        return $this->belongsTo(Person::class, 'peopleId');
    }

    public function house()
    {
        return $this->belongsTo(Housing::class, 'houseId');
    }

    public function responsible()
    {
        return $this->belongsTo(Person::class, 'responsibleId');
    }
}
