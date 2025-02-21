<?php
// app/Models/Sport.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;

    protected $table = 'sports'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'id',
        'name',
        'description',
        'imageId',
        'status',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
        'status' => 'boolean', // Cast status as boolean
    ];
}
