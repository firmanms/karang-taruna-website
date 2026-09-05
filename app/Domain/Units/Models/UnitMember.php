<?php

namespace App\Domain\Units\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'full_name',
        'position_role',
        'division_section',
        'phone',
        'email',
        'photo_path',
        'order_index',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
    }
}
