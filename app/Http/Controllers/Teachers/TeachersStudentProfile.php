<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachersStudentProfile extends Controller
{
    public function index(Request $request)
    {
        $student_data = [];
        $grade_data = [];
        $grade_all = [];
        $activity_data = [];
        $suggestions = []; // สำหรับเก็บรายชื่อที่ซ้ำ
        $sum_grade = 0;
        $sum_act = 0;

        if ($request->student_id != null) {
            $search = $request->student_id;

            // ค้นหาจากทุกตารางเพื่อหาความเป็นไปได้ทั้งหมด
            for ($i = 1; $i <= 3; $i++) {
                $matches = DB::table("student{$i}")
                    ->where('ID', $search)
                    ->orWhere('CARDID', $search)
                    ->orWhere('NAME', 'LIKE', "%{$search}%")
                    ->orWhere('SURNAME', 'LIKE', "%{$search}%")
                    ->select('ID', 'NAME', 'SURNAME', 'CARDID', 'GRP_CODE', DB::raw("$i as level"))
                    ->get();

                foreach ($matches as $m) {
                    $suggestions[] = $m;
                }
            }

            // กรณีที่ 1: ไม่พบข้อมูลเลย
            if (count($suggestions) == 0) {
                return view('teachers.tstudentprofile', compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act'))
                    ->withErrors(['student_id' => 'ไม่พบข้อมูลที่ระบุ']);
            }

            // กรณีที่ 2: เจอหลายคน (แสดงรายชื่อให้เลือก)
            // หรือกรณีค้นด้วยชื่อแล้วเจอคนเดียว แต่เราต้องการให้กดตกลงก่อน
            if (count($suggestions) > 1 && !$request->has('confirmed_id')) {
                return view('teachers.tstudentprofile', compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act', 'suggestions'));
            }

            // กรณีที่ 3: เจอคนเดียว หรือ ผู้ใช้กดเลือกจากรายการมาแล้ว (confirmed_id)
            $target_id = $request->confirmed_id ?? $suggestions[0]->ID;
            $found_level = $request->level ?? $suggestions[0]->level;

            // --- Logic การดึงข้อมูลโปรไฟล์ (เหมือนเดิมแต่ใช้ $target_id) ---
            $tgrade = 'grade' . $found_level;
            $tstudent = 'student' . $found_level;
            $tsubject = 'subject' . $found_level;
            $tactivity = 'activity' . $found_level;

            $student_data = DB::table($tstudent)->where('ID', $target_id)->get();
            if ($student_data->isEmpty()) {
                return view('teachers.tstudentprofile', compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act', 'grade_all'))
                    ->withErrors(['student_id' => 'ไม่พบข้อมูลที่ระบุ']);
            }
            $student = $student_data->first();
            $std_code = $student->STD_CODE;

            // บันทึกประวัติการเข้าถึงข้อมูลส่วนบุคคล (Audit Trail / Access Log) ตามกฎหมาย PDPA
            $studentName = ($student->PRENAME ?? '') . ($student->NAME ?? '') . ' ' . ($student->SURNAME ?? '');
            
            // 1. บันทึกประวัติลงไฟล์ log ท้องถิ่น
            \Illuminate\Support\Facades\Log::info(sprintf(
                "AUDIT: Teacher %s (ID: %d) accessed student profile. Student Code: %s, Name: %s, IP: %s, UserAgent: %s",
                \Illuminate\Support\Facades\Auth::user()->name,
                \Illuminate\Support\Facades\Auth::id(),
                $std_code,
                $studentName,
                $request->ip(),
                $request->userAgent()
            ));

            // 2. บันทึกประวัติลงฐานข้อมูลตาราง audit_logs (หากตารางนี้มีอยู่แล้ว)
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
                    DB::table('audit_logs')->insert([
                        'user_id' => \Illuminate\Support\Facades\Auth::id(),
                        'user_name' => \Illuminate\Support\Facades\Auth::user()->name,
                        'action' => 'view_profile',
                        'target_id' => $target_id,
                        'target_code' => $std_code,
                        'target_name' => $studentName,
                        'ip_address' => $request->ip(),
                        'user_agent' => substr($request->userAgent(), 0, 255),
                        'created_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to insert audit log to database: " . $e->getMessage());
            }

            $grade_data = DB::table($tgrade)
                ->where('STD_CODE', '=', $std_code)
                ->where('GRADE', '>', 0)
                ->join($tsubject, $tgrade . '.SUB_CODE', '=', $tsubject . '.SUB_CODE')
                ->select($tsubject.'.*', $tgrade.'.GRADE', $tgrade.'.SEMESTRY')
                ->orderByDesc('SEMESTRY')->get();

            $grade_all = DB::table($tgrade)
                ->where('STD_CODE', '=', $std_code)
                ->join($tsubject, $tgrade . '.SUB_CODE', '=', $tsubject . '.SUB_CODE')
                ->select($tsubject.'.*', $tgrade.'.GRADE', $tgrade.'.SEMESTRY')
                ->orderByDesc('SEMESTRY')->get();

            $activity_data = DB::table($tactivity)->where('STD_CODE', '=', $std_code)->get();
            $sum_grade = $grade_data->sum('SUB_CREDIT');
            $sum_act = $activity_data->sum('HOUR');

            return view('teachers.tstudentprofile', compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act', 'grade_all'));
        }

        return view('teachers.tstudentprofile', compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act', 'grade_all'));
    }
}