<?php

namespace App\Domain\PPKS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpksCheckLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'searched_nik_masked',
        'ip_address',
        'user_agent',
        'result_status',
        'searched_at',
    ];

    protected function casts(): array
    {
        return [
            'searched_at' => 'datetime',
        ];
    }
}
