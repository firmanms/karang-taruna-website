<?php

namespace App\Domain\Territory\Models;

use App\Domain\Units\Models\KarangTarunaUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefDistrict extends Model
{
    use HasFactory;

    protected $fillable = [
        'kemendagri_code',
        'name',
        'slug',
        'latitude_center',
        'longitude_center',
        'geojson_boundary',
    ];

    public function villages(): HasMany
    {
        return $this->hasMany(RefVillage::class, 'district_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(KarangTarunaUnit::class, 'district_id');
    }
}
