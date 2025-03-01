<?php
// app/Models/SportClass.php
namespace App\Models;

use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportClass extends Model
{
    use HasFactory;

    protected $table = 'sport_classes'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'id',
        'sportId',
        'type',
        'classOption',
        'imageId',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
    ];

    // Relasi ke model Provinces
    public function sports()
    {
        return $this->belongsTo(Sport::class, 'sportId', 'id');
    }
}
