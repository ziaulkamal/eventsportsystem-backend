<?php
// app/Models/Venue.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $table = 'venues'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'id',
        'name',
        'location',
        'latitude',
        'longitude',
        'status',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
        'latitude' => 'decimal:8', // Cast latitude as decimal
        'longitude' => 'decimal:8', // Cast longitude as decimal
    ];
}
