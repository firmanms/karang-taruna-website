<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'order_index',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order_index' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function workPrograms(): HasMany
    {
        return $this->hasMany(WorkProgram::class, 'budget_source_id');
    }
}
