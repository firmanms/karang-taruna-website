<?php

namespace App\Domain\PPKS\Models;

use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpksBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'full_name',
        'category_id',
        'district_id',
        'village_id',
        'unit_id',
        'submitted_by',
        'address_detail',
        'social_assistance_status',
        'mentor_unit',
        'last_survey_date',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected function casts(): array
    {
        return [
            'last_survey_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PpksCategory::class, 'category_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(RefDistrict::class, 'district_id');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(RefVillage::class, 'village_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getMaskedNikAttribute(): string
    {
        if (strlen($this->nik) >= 16) {
            return substr($this->nik, 0, 6).'******'.substr($this->nik, -4);
        }

        return '******';
    }
}
