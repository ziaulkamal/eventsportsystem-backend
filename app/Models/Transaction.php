<?php
// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'ticketId',
        'customer_name',
        'whatsapp_number',
        'ticket_type',
        'quantity',
        'used',
        'total_price',
        'status',
        'data',
        'ticket_number',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
        'total_price' => 'decimal:2', // Cast total_price as decimal
        'data' => 'json', // Cast data as JSON
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticketId');
    }
}
