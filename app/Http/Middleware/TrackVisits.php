<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $today = now()->toDateString();
        $ip = $request->ip();
        $user = $request->user();

        // Use a unique key for cache to prevent hammering DB on every request for same user/day
        // Key format: visit:Y-m-d:ip (for guests) or visit:Y-m-d:uid (for users)
        $key = 'visit:' . $today . ':' . ($user ? 'u' . $user->id : 'ip' . md5($ip));

        if (! cache()->has($key)) {
            try {
                \App\Models\Visit::firstOrCreate(
                    [
                        'visit_date' => $today,
                        'ip_address' => $ip,
                        'user_id' => $user?->id,
                    ]
                );
                // Cache for 24h (or until end of day) to avoid DB hit
                cache()->put($key, true, now()->endOfDay());
            } catch (\Exception $e) {
                // Ignore duplicate entry errors or race conditions
            }
        }

        return $next($request);
    }
}
