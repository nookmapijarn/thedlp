<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogRequestTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // จัดเก็บข้อมูลลงใน secure storage (storage/logs/traffic-YYYY-MM-DD.log) 
        // สอดคล้องกับพ.ร.บ.คอมพิวเตอร์ฯ ม.26 (จัดเก็บข้อมูลจราจรอย่างน้อย 90 วัน)
        try {
            $logData = sprintf(
                "[%s] IP: %s | User: %s | Method: %s | URL: %s | Status: %d | UA: %s",
                now()->format('Y-m-d H:i:s'),
                $request->ip(),
                Auth::check() ? 'User#' . Auth::id() . ' (' . Auth::user()->name . ')' : 'Guest',
                $request->method(),
                $request->fullUrl(),
                $response->status(),
                $request->userAgent()
            );

            $logPath = storage_path('logs/traffic-' . now()->format('Y-m-d') . '.log');
            file_put_contents($logPath, $logData . PHP_EOL, FILE_APPEND | LOCK_EX);
        } catch (\Exception $e) {
            // ป้องกันการแครชของเว็บแอปพลิเคชัน
            Log::error("Traffic logging failed: " . $e->getMessage());
        }

        return $response;
    }
}
