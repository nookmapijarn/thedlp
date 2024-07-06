<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\EmailLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        $u_role = Auth::user()->role;

        if($u_role == 1){
            return redirect()->route('ประวัติการเรียน');
        } 
        if ($u_role == 2) {
            return redirect()->route('tdashboard');  
        } 
        if ($u_role == 3) {
            return redirect()->route('boss');  
        } 
        if ($u_role == 4) {
            return redirect()->route('admin'); 
        } 
        
        return redirect('welcome/?roletype=' . $u_role);
    }

    public function storeWithEmail (EmailLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        $u_role = Auth::user()->role;

        if($u_role == 1){
            return redirect()->route('ประวัติการเรียน');
        } 
        if ($u_role == 2) {
            return redirect()->route('tdashboard');  
        } 
        if ($u_role == 3) {
            return redirect()->route('boss');  
        } 
        if ($u_role == 4) {
            return redirect()->route('admin'); 
        } 
        
        return redirect('welcome/?roletype=' . $u_role);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
