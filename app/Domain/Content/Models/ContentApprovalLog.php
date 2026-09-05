<?php

namespace App\Domain\Content\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentApprovalLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'content_type',
        'content_id',
        'submitted_by',
        'reviewed_by',
        'action_status',
        'review_notes',
        'action_timestamp',
    ];

    protected function casts(): array
    {
        return [
            'action_timestamp' => 'datetime',
        ];
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
