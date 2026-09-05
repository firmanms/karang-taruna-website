<?php

namespace App\Domain\Content\Models;

use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use App\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkProgram extends Model
{
    use HasFactory, HasTenantScope;

    protected $fillable = [
        'division_id',
        'unit_id',
        'user_id',
        'program_name',
        'slug',
        'program_code',
        'target_participants',
        'output_indicators',
        'budget_amount',
        'budget_source_id',
        'budget_source',
        'execution_time',
        'poster_image',
        'short_description',
        'detailed_description',
        'progress_status',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'is_featured_home',
    ];

    protected function casts(): array
    {
        return [
            'budget_amount' => 'decimal:2',
            'is_featured_home' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(ProgramDivision::class, 'division_id');
    }

    public function budgetSource(): BelongsTo
    {
        return $this->belongsTo(BudgetSource::class, 'budget_source_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(KarangTarunaUnit::class, 'unit_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
