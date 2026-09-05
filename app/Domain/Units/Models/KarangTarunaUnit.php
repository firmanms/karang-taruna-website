<?php

namespace App\Domain\Units\Models;

use App\Domain\Content\Models\Achievement;
use App\Domain\Content\Models\Announcement;
use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\PhotoGallery;
use App\Domain\Content\Models\WorkProgram;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Models\User;
use App\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KarangTarunaUnit extends Model
{
    use HasFactory, HasTenantScope;

    protected $fillable = [
        'unit_level',
        'district_id',
        'village_id',
        'rw_number',
        'unit_name',
        'unit_code',
        'slug',
        'chairman_name',
        'secretary_name',
        'treasurer_name',
        'contact_phone',
        'contact_email',
        'office_address',
        'office_photo',
        'latitude',
        'longitude',
        'sk_number',
        'sk_file_path',
        'period_start_year',
        'period_end_year',
        'total_members',
        'status_aktif',
        'logo_path',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(RefDistrict::class, 'district_id');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(RefVillage::class, 'village_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(UnitMember::class, 'unit_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unit_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'unit_id');
    }

    public function workPrograms(): HasMany
    {
        return $this->hasMany(WorkProgram::class, 'unit_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'unit_id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'unit_id');
    }

    public function photoGalleries(): HasMany
    {
        return $this->hasMany(PhotoGallery::class, 'unit_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'unit_id');
    }

    /**
     * Helper URL publik detail unit
     */
    public function getPublicUrlAttribute(): string
    {
        if ($this->unit_level === 'kecamatan') {
            return route('public.district.detail', $this->slug ?? $this->id);
        }

        if ($this->unit_level === 'desa') {
            return route('public.village.detail', $this->slug ?? $this->id);
        }

        return route('public.profile');
    }
}
