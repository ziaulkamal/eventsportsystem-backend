<?php

namespace App\Models;

use App\Models\Schedule;
use App\Models\Sport;
use App\Models\SportClass;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'matches';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'sport_id',
        'venue_id',
        'sport_class_id',
        'schedule_id',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function sportClass()
    {
        return $this->belongsTo(SportClass::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
