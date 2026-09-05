<?php

namespace App\Domain\Content\Models;

use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use App\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory, HasTenantScope;

    protected $fillable = [
        'category_id',
        'user_id',
        'unit_id',
        'district_id',
        'village_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'image_caption',
        'news_scope',
        'tags',
        'views_count',
        'is_featured',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved')
            ->where('is_published', true);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
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
