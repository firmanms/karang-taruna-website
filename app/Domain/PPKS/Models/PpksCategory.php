<?php

namespace App\Domain\PPKS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpksCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_code',
        'category_name',
        'description',
    ];

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(PpksBeneficiary::class, 'category_id');
    }
}
