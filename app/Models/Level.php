<?php
// app/Models/Level.php
namespace App\Models;

use App\Models\ParentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $table = 'levels'; // Set table name
    protected $primaryKey = 'id'; // Set primary key


    protected $fillable = [
        'name',
        'role',
        'parentId',
    ];

    /**
     * Relasi ke model Parent.
     * Setiap Level memiliki hubungan belongsTo dengan Parent.
     */
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parentId');
    }

    /**
     * Relasi ke model Level untuk parent-child relationship.
     * Menggunakan parentId untuk membangun relasi rekursif.
     */
    public function children()
    {
        return $this->hasMany(Level::class, 'parentId');
    }
}
