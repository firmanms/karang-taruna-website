<?php

namespace App\Domain\Statistics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorAggregate extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_date',
        'daily_visits',
        'unique_visitors',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
        ];
    }
}
