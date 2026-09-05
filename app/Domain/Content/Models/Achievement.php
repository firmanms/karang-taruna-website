<?php

namespace App\Domain\Content\Models;

use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'user_id',
        'district_id',
        'village_id',
        'title',
        'slug',
        'recipient_name',
        'achievement_level',
        'category_field',
        'year',
        'rank_position',
        'awarded_by',
        'description',
        'certificate_image',
        'is_featured',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
    }

    public function creator(): BelongsTo
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
