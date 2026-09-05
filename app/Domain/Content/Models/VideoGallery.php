<?php

namespace App\Domain\Content\Models;

use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'user_id',
        'title',
        'slug',
        'category',
        'video_platform',
        'video_url',
        'video_id',
        'thumbnail_path',
        'duration_text',
        'description',
        'is_featured',
        'approval_status',
        'approved_by',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
