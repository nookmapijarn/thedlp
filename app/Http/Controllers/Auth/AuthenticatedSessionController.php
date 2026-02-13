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

        return $this->handleRoleRedirect();
    }

    public function storeWithEmail(EmailLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return $this->handleRoleRedirect();
    }

    /**
     * ฟังก์ชันจัดการการเปลี่ยนเส้นทางตามสิทธิ์ (Role) 
     * และรองรับ Redirect ไปหน้าเดิมที่ผู้ใช้กดมา (Intended)
     */
    protected function handleRoleRedirect(): RedirectResponse
    {
        $user = Auth::user();
        $u_role = $user->role;

        // กำหนดหน้า Default ตาม Role ในกรณีที่ไม่ได้กดมาจากหน้าอื่น
        $defaultRoute = url('welcome/?roletype=' . $u_role);

        if ($u_role == 1) {
            $defaultRoute = route('ประวัติการเรียน');
        } elseif ($u_role == 2) {
            $defaultRoute = route('tdashboard');
        } elseif ($u_role == 3) {
            $defaultRoute = route('boss');
        } elseif ($u_role == 4) {
            $defaultRoute = route('admin');
        }

        // redirect()->intended() จะส่งกลับหน้าเดิมที่ตั้งใจจะเข้า
        // ถ้าไม่มีหน้าเดิม จะส่งไปที่ $defaultRoute
        return redirect()->intended($defaultRoute);
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