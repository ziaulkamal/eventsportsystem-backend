<?php
// app/Models/Schedule.php
namespace App\Models;

use App\Models\SportClass;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'venueId',
        'sportClassId',
        'status',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
        'date' => 'date', // Cast date as date
        'start_time' => 'datetime:H:i', // Cast start_time as time
        'end_time' => 'datetime:H:i', // Cast end_time as time
    ];

    // Relationships
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venueId');
    }

    public function sportClass()
    {
        return $this->belongsTo(SportClass::class, 'sportClassId');
    }
}
