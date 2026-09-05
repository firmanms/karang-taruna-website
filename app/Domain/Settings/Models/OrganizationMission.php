<?php

namespace App\Domain\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationMission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'profile_id',
        'mission_text',
        'order_index',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(ProfileOrganization::class, 'profile_id');
    }
}
