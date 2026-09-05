<?php

namespace App\Domain\Territory\Models;

use App\Domain\Units\Models\KarangTarunaUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefVillage extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'kemendagri_code',
        'name',
        'slug',
        'type',
        'postal_code',
        'total_rw',
        'total_rt',
        'latitude_center',
        'longitude_center',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(RefDistrict::class, 'district_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(KarangTarunaUnit::class, 'village_id');
    }
}
