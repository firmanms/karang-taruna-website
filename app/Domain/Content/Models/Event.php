<?php

namespace App\Domain\Content\Models;

use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use App\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory, HasTenantScope;

    protected $fillable = [
        'category_id',
        'unit_id',
        'user_id',
        'district_id',
        'village_id',
        'title',
        'slug',
        'event_date',
        'start_time',
        'end_time',
        'location_venue',
        'location_address',
        'latitude',
        'longitude',
        'organizer',
        'description',
        'registration_link',
        'event_status',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'thumbnail_image',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'latitude' => 'float',
            'longitude' => 'float',
            'approved_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(RefDistrict::class, 'district_id');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(RefVillage::class, 'village_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
