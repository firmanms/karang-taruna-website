<?php

namespace App\Domain\Content\Models;

use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'unit_id',
        'user_id',
        'district_id',
        'title',
        'caption_description',
        'image_path',
        'location',
        'event_date',
        'order_index',
        'approval_status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PhotoCategory::class, 'category_id');
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

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
