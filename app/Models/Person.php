<?php

namespace App\Models;

use App\Models\Coach;
use App\Models\Kemendagri\Districts;
use App\Models\Kemendagri\Provinces;
use App\Models\Kemendagri\Regencies;
use App\Models\Kemendagri\Villages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'people'; // Set table name
    protected $primaryKey = 'id'; // Set primary key
    public $incrementing = false; // Disable auto-increment for UUID
    protected $keyType = 'string'; // Set key type to string for UUID

    protected $fillable = [
        'id',
        'fullName',
        'age',
        'birthdate',
        'identityNumber',
        'familyIdentityNumber',
        'gender',
        'streetAddress',
        'religion',
        'provinceId',
        'regencieId',
        'districtId',
        'villageId',
        'phoneNumber',
        'email',
        'documentId',
        'userId',
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is cast as string
        'birthdate' => 'date:Y-m-d', // Cast birthdate as date
    ];

    // Relasi ke model Athlete
    public function athlete()
    {
        return $this->hasOne(Athlete::class, 'peopleId');
    }

    public function coach() {
        return $this->hasOne(Coach::class, 'peopleId');
    }
    // Relasi ke model Document
    public function document()
    {
        return $this->hasOne(Document::class, 'id', 'documentId');
    }

    /**
     * Get the province that owns the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Provinces::class, 'provinceId', 'id');
    }

    /**
     * Get the regencie that owns the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regencie()
    {
        return $this->belongsTo(Regencies::class, 'regencieId', 'id');
    }

    /**
     * Get the district that owns the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(Districts::class, 'districtId', 'id');
    }

    /**
     * Get the village that owns the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function village()
    {
        return $this->belongsTo(Villages::class, 'villageId', 'id');
    }
}
