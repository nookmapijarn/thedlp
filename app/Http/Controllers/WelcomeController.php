<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User; 

class WelcomeController extends Controller
{
    public function index()
    {
        // 1. หาภาคเรียนล่าสุด
        $currentSemester = DB::table('grade1')->max('SEMESTRY');
        if (!$currentSemester) {
            $currentSemester = DB::table('grade2')->max('SEMESTRY') ?: DB::table('grade3')->max('SEMESTRY');
        }

        // 2. นับจำนวนนักศึกษาตามภาคเรียน (จากตารางเกรด)
        if ($currentSemester) {
            $g1 = DB::table('grade1')->where('SEMESTRY', $currentSemester)->select('STD_CODE')->distinct();
            $g2 = DB::table('grade2')->where('SEMESTRY', $currentSemester)->select('STD_CODE')->distinct();
            
            $allusers = DB::table('grade3')
                ->where('SEMESTRY', $currentSemester)
                ->select('STD_CODE')
                ->distinct()
                ->union($g1)
                ->union($g2)
                ->get()
                ->count();
        } else {
            $allusers = 0;
        }

        // 3. นับคนออนไลน์ขณะนี้ (ภายใน 5 นาทีล่าสุด)
        $onlineCount = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        // 4. นับคนใช้งาน "วันนี้" แยกตาม Role (Daily Active Users)
        $todayUsers = User::whereDate('last_seen_at', Carbon::today())->count();
        
        // แยกวันนี้: ผู้เรียน (1) และ ครู (2)
        $todayStudents = User::where('role', 1)->whereDate('last_seen_at', Carbon::today())->count();
        $todayTeachers = User::where('role', 2)->whereDate('last_seen_at', Carbon::today())->count();

        // 5. สมาชิกทั้งหมดในระบบ แยกตาม Role
        $totalMembers = User::count();
        $totalStudents = User::where('role', 1)->count();
        $totalTeachers = User::where('role', 2)->count();

        return view('welcome', compact(
            'allusers', 
            'todayUsers', 
            'todayStudents',
            'todayTeachers',
            'totalMembers', 
            'totalStudents',
            'totalTeachers',
            'currentSemester', 
            'onlineCount'
        ));
    }
}