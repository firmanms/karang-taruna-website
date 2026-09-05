<?php

namespace App\Domain\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileOrganization extends Model
{
    use HasFactory;

    protected $table = 'profile_organization';

    protected $fillable = [
        'org_name',
        'legal_basis',
        'history_content',
        'vision',
        'logo_path',
        'sk_number',
        'sk_file_path',
        'period_years',
        'address_office',
        'office_photo',
        'latitude',
        'longitude',
        'email_official',
        'phone_official',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function missions(): HasMany
    {
        return $this->hasMany(OrganizationMission::class, 'profile_id');
    }
}
