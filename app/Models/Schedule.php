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
        'id',
        'date',
        'venueId',
        'sportId',
        'sportClassId',
        'status',
        'userId',
    ];

    protected $casts = [
        // 'id' => 'uuid',
        'date' => 'date',  // Format tanggal standar
    ];


    // Relationships
    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sportId');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venueId');
    }

    public function sportClass()
    {
        return $this->belongsTo(SportClass::class, 'sportClassId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
