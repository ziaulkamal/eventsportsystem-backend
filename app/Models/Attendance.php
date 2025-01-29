<?php
// app/Models/Attendance.php
namespace App\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'transactionId',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
    ];

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactionId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
