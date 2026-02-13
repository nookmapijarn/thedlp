<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateLastSeenAt
{
    public function handle(Request $request, Closure $next)
    {
        // ถ้าผู้ใช้ล็อกอินอยู่ ให้บันทึกเวลาปัจจุบัน
        if (Auth::check()) {
            DB::table('users')
                ->where('id', Auth::id())
                ->update(['last_seen_at' => now()]);
        }

        return $next($request);
    }
}