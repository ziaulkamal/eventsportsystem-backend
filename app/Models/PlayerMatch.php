<?php
// app/Models/PlayerMatch.php
namespace App\Models;

use App\Models\Athlete;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerMatch extends Model
{
    use HasFactory;

    protected $table = 'player_matches'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'athleteId',
        'scheduleId',
        'grade',
        'score',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
        'grade' => 'decimal:2', // Cast grade as decimal
        'score' => 'decimal:2', // Cast score as decimal
    ];

    // Relationships
    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athleteId');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'scheduleId');
    }
}
