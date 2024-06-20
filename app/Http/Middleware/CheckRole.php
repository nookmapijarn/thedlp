<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // if($role )
        if (auth()->check() && auth()->user()->role == $role) {
            return $next($request);
        }

        return redirect('welcome/?roletype=' . auth()->user()->role);
    }
}
