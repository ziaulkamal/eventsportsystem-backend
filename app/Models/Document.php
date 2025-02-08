<?php

namespace App\Models;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str untuk generate UUID

class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // UUID sebagai primary key
    public $incrementing = false; // Non-incrementing UUID
    protected $keyType = 'string'; // Tipe data primary key

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'id',
        'docsKtp',
        'docsIjazah',
        'docsSim',
        'docsAkte',
        'docsTransport',
        'docsSelfieKtp',
        'docsImageProfile',
        'userId',
    ];

    // Sembunyikan field biner dari respons JSON
    protected $hidden = [
        'userId',
    ];

    // Cast kolom `extra` ke tipe array/JSON
    protected $casts = [
        'extra' => 'array',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'id', 'documentId');
    }

}
