<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramDivision extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'division_name',
        'slug',
        'description',
        'order_index',
    ];

    public function workPrograms(): HasMany
    {
        return $this->hasMany(WorkProgram::class, 'division_id');
    }
}
