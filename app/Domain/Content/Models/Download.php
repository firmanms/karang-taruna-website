<?php

namespace App\Domain\Content\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'document_title',
        'description',
        'file_path',
        'file_format',
        'file_size_kb',
        'download_count',
        'release_date',
        'approval_status',
        'approved_by',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'is_public' => 'boolean',
        ];
    }

    public function scopePublicDownloads(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved')
            ->where('is_public', true);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DownloadCategory::class, 'category_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
