<?php

namespace App\Domain\Settings\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'session_id',
        'user_agent',
        'page_url',
        'visit_date',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'last_activity_at' => 'datetime',
        ];
    }

    /**
     * Hitung total statistik pengunjung
     */
    public static function getVisitorStats(): array
    {
        $today = Carbon::today()->toDateString();
        $thisMonthStart = Carbon::now()->startOfMonth()->toDateString();
        $thisMonthEnd = Carbon::now()->endOfMonth()->toDateString();
        $onlineThreshold = Carbon::now()->subMinutes(5);

        // Kunjungan unik per IP per hari
        $todayCount = static::where('visit_date', $today)->count();
        $monthCount = static::whereBetween('visit_date', [$thisMonthStart, $thisMonthEnd])->count();
        $totalCount = static::count();
        $onlineCount = static::where('last_activity_at', '>=', $onlineThreshold)->count();

        // Jamin nilai minimal realistis jika database baru
        $baseOffset = 1500; // Akumulasi awal portal
        $baseMonthOffset = 180;
        $baseTodayOffset = 25;

        return [
            'today' => number_format($todayCount + $baseTodayOffset, 0, ',', '.'),
            'month' => number_format($monthCount + $baseMonthOffset, 0, ',', '.'),
            'total' => number_format($totalCount + $baseOffset, 0, ',', '.'),
            'online' => max(1, $onlineCount),
        ];
    }
}
