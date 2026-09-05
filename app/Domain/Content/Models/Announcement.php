<?php

namespace App\Domain\Content\Models;

use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unit_id',
        'title',
        'slug',
        'announcement_number',
        'category',
        'excerpt',
        'content',
        'attachment_file',
        'valid_until',
        'is_pinned',
        'approval_status',
        'approved_by',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'is_pinned' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved')
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now()->toDateString());
            });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
