<?php
// app/Models/Ticket.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'regular_quota',
        'regular_price',
        'silver_quota',
        'silver_price',
        'gold_quota',
        'gold_price',
        'platinum_quota',
        'platinum_price',
        'status',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
    ];
}
