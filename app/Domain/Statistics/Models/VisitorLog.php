<?php

namespace App\Domain\Statistics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'session_id',
        'visit_date',
        'last_activity',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'last_activity' => 'datetime',
        ];
    }
}
