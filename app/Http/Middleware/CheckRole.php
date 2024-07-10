<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $userrole = Auth::user()->role;
        // Log::info('ck userrole ***************** '.$userrole);
        // Log::info('ck role ***************** '.$role);

        if (Auth::check()) {
            if (Auth::user()->role == $role) {
                return $next($request);
            } else {
                return redirect('welcome/?roletype=' . Auth::user()->role);
            }
        }
    
        // หากไม่ได้รับการรับรองความถูกต้อง ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
        return redirect()->route('login');
    }
}
