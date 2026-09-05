<?php

namespace App\Http\Middleware;

use App\Domain\Settings\Models\SiteVisitorLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Abaikan request aset, livewire, dan filament admin internal
        if ($request->is('admin*') || $request->is('livewire*') || $request->is('api*') || $request->is('_debugbar*')) {
            return $next($request);
        }

        try {
            $ip = $request->ip();
            $today = Carbon::today()->toDateString();
            $sessionId = session()->getId();
            $userAgent = substr($request->userAgent() ?? '', 0, 500);
            $pageUrl = substr($request->fullUrl(), 0, 255);

            // Update log atau buat baru untuk IP pada hari ini
            $log = SiteVisitorLog::where('visit_date', $today)
                ->where('ip_address', $ip)
                ->first();

            if ($log) {
                $log->update([
                    'last_activity_at' => now(),
                    'page_url' => $pageUrl,
                ]);
            } else {
                SiteVisitorLog::create([
                    'ip_address' => $ip,
                    'session_id' => $sessionId,
                    'user_agent' => $userAgent,
                    'page_url' => $pageUrl,
                    'visit_date' => $today,
                    'last_activity_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Lanjutkan jika ada kegagalan log
        }

        return $next($request);
    }
}
