<?php

namespace App\Domain\Statistics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerritoryStatistic extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'stat_key',
        'stat_label',
        'stat_value',
        'icon_class',
    ];
}
