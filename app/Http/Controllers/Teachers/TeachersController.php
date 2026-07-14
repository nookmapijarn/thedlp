<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use App\Models\Student1;
use App\Models\Student2;
use App\Models\Student3;
use App\Models\Group;
use App\Models\Course;
use App\Models\ShortVideo;
use App\Models\Quiz;

class TeachersController extends Controller
{
    public function index(Request $request)
    {   
        ini_set('max_execution_time', '256M');

        $agent = new Agent();  
        $limit = $agent->isMobile() ? 4 : 6; 
        $labels = $this->get_semestry($limit);
        $labels = array_reverse($labels);

        $current_semestry = $this->get_semestry(1)[0];
        
        $data_student = $this->get_student_counts($labels);
        // print_r($data_student['data_student']);

        // $province = $this->province();
        $all_tumbon = $this->get_group($current_semestry);
        $student_tumbon = $this->get_student_tumbon_counts($current_semestry, $all_tumbon); //จำนวนรายตำบล

        $courses = Course::where('teacher_id', auth()->id())->latest()->get();
        $shorts = ShortVideo::where('teacher_id', auth()->id())->latest()->get();
        $quizzes = Quiz::where('created_by', auth()->id())->latest()->get();

        // 1. ผู้เรียนที่เข้าเรียนในระบบสูงสุด (Active Students by Audit Log Activity)
        $top_students = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->where('users.role', 1)
            ->select('users.name', 'users.email', DB::raw('count(audit_logs.id) as logs_count'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('logs_count', 'desc')
            ->take(5)
            ->get();

        // 2. ครูผู้สร้าง ผลงานมากที่สุด (หลักสูตร, คลิปสั้น, แบบทดสอบ)
        $teachers_list = \App\Models\User::where('role', 2)->get();
        $teacher_rankings = [];
        foreach ($teachers_list as $t) {
            $courses_count = \App\Models\Course::where('teacher_id', $t->id)->count();
            $shorts_count = \App\Models\ShortVideo::where('teacher_id', $t->id)->count();
            $quizzes_count = \App\Models\Quiz::where('created_by', $t->id)->count();
            $total_creations = $courses_count + $shorts_count + $quizzes_count;
            if ($total_creations > 0) {
                $teacher_rankings[] = [
                    'name' => $t->name,
                    'courses' => $courses_count,
                    'shorts' => $shorts_count,
                    'quizzes' => $quizzes_count,
                    'total' => $total_creations
                ];
            }
        }
        usort($teacher_rankings, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        $top_teachers = array_slice($teacher_rankings, 0, 5);

        // 3. คลิปที่มีผู้กดไลค์สูงสุด
        $top_liked_shorts = \App\Models\ShortVideo::with('teacher')
            ->orderBy('likes_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // 4. หลักสูตรที่มีผู้เรียนมากที่สุด
        $top_courses = \App\Models\Course::with('teacher')
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        return view('teachers.tdashboard', compact(
            'labels', 
            'data_student', 
            'all_tumbon', 
            'student_tumbon', 
            'current_semestry', 
            'courses', 
            'shorts', 
            'quizzes',
            'top_students',
            'top_teachers',
            'top_liked_shorts',
            'top_courses'
        ));
    }

    public function get_student_counts($labels)
    {
        $data_student = [];
        $data_new_student = [];
        $data_old_student = [];
        
        foreach($labels as $val) {
            $allstudent = $this->current_student_count($val);
            $new_student = $this->new_student_count($val);

            $data_student[] = $allstudent;
            $data_new_student[] = $new_student;
            $data_old_student[] = $allstudent - $new_student;
        }

        return [
            'data_student' => $data_student,
            'data_new_student' => $data_new_student,
            'data_old_student' => $data_old_student
        ];
    }

    public function current_student_count($semestry)
    {
        $count = 0;
        for ($i = 1; $i <= 3; $i++) {
            $count += DB::table("grade{$i}")
                ->where("grade{$i}.SEMESTRY", $semestry)
                ->distinct("grade{$i}.STD_CODE")
                ->count("grade{$i}.STD_CODE");
        }
        return $count;
    }
    

    public function new_student_count($semestry)
    {
        $ID = str_replace('/', '', $semestry);
        $count = 0;
        for ($i = 1; $i <= 3; $i++) {
            $count += DB::table("student{$i}")
                ->where("student{$i}.ID", "like", "{$ID}%")
                ->count("student{$i}.ID");
        }
    
        return $count;
    }

    public function get_student_tumbon_counts($semestry, $all_tumbon)
    {
        $grpCodes = $all_tumbon->pluck('GRP_CODE')->toArray();
        if (empty($grpCodes)) {
            return [];
        }
    
        // Run a single combined query with whereIn instead of querying in a loop!
        $query = DB::table('grade1')
            ->select('GRP_CODE', DB::raw("'ST1' as grade"), DB::raw("COUNT(DISTINCT STD_CODE) as student_count"))
            ->where('SEMESTRY', $semestry)
            ->whereIn('GRP_CODE', $grpCodes)
            ->groupBy('GRP_CODE')
            ->unionAll(
                DB::table('grade2')
                    ->select('GRP_CODE', DB::raw("'ST2' as grade"), DB::raw("COUNT(DISTINCT STD_CODE) as student_count"))
                    ->where('SEMESTRY', $semestry)
                    ->whereIn('GRP_CODE', $grpCodes)
                    ->groupBy('GRP_CODE')
            )
            ->unionAll(
                DB::table('grade3')
                    ->select('GRP_CODE', DB::raw("'ST3' as grade"), DB::raw("COUNT(DISTINCT STD_CODE) as student_count"))
                    ->where('SEMESTRY', $semestry)
                    ->whereIn('GRP_CODE', $grpCodes)
                    ->groupBy('GRP_CODE')
            );
    
        $results = $query->get();
    
        // Map results by GRP_CODE and grade in memory
        $mapped = [];
        foreach ($results as $result) {
            $mapped[$result->GRP_CODE][$result->grade] = $result->student_count;
        }
    
        $student_tumbon = [];
        foreach ($all_tumbon as $tb) {
            $student_count = [
                'ST1' => $mapped[$tb->GRP_CODE]['ST1'] ?? 0,
                'ST2' => $mapped[$tb->GRP_CODE]['ST2'] ?? 0,
                'ST3' => $mapped[$tb->GRP_CODE]['ST3'] ?? 0,
            ];
    
            $student_tumbon[] = [
                'GRP' => $tb,
                'STUDENT' => $student_count,
            ];
        }
    
        return $student_tumbon;
    }
    

    public function get_semestry($limit)
    {
        return DB::table('grade3')
            ->select('SEMESTRY')
            ->orderBy('SEMESTRY', 'DESC')
            ->groupBy('SEMESTRY')
            ->take($limit)
            ->pluck('SEMESTRY')
            ->toArray();
    }

    public function province()
    {
        $current_semestry = $this->get_semestry(1)[0];
        return [
            ["hc-key" => "th-sh", "name" => 'สพรรณบุรี', "value" => Student1::where('ZIPCODE', '>=', 72000)->where('ZIPCODE', '<', 73000)->count()],
            ["hc-key" => "th-at", "name" => 'อ่างทอง', "value" => Student1::where('ZIPCODE', '>=', 14000)->where('ZIPCODE', '<', 15000)->count()],
            // Add more provinces as needed
        ];
    }

    public function get_group($semestry)
    {
        // ใช้ UNION ALL เพื่อรวมข้อมูลจากตาราง grade1, grade2, และ grade3
        $query = DB::table('grade1')
            ->select('GRP_CODE')
            ->where('SEMESTRY', $semestry)
            ->unionAll(
                DB::table('grade2')
                    ->select('GRP_CODE')
                    ->where('SEMESTRY', $semestry)
            )
            ->unionAll(
                DB::table('grade3')
                    ->select('GRP_CODE')
                    ->where('SEMESTRY', $semestry)
            );
    
        // ดึงข้อมูล GRP_CODE ที่ไม่ซ้ำกัน
        $uniqueGrpCodes = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->select('GRP_CODE')
            ->groupBy('GRP_CODE')
            ->get()
            ->pluck('GRP_CODE');
    
        // ดึงข้อมูลกลุ่ม (group) จากตาราง group โดยใช้ GRP_CODE ที่ไม่ซ้ำกัน
        $groups = DB::table('group')
            ->select('GRP_CODE', 'GRP_NAME', 'GRP_ADVIS')
            ->whereIn('GRP_CODE', $uniqueGrpCodes)
            ->get();
    
        return $groups;
    }

    /**
     * Show the profile edit page for a teacher.
     */
    public function editProfile()
    {
        $teacher = auth()->user();
        return view('teachers.profile', compact('teacher'));
    }

    /**
     * Update the teacher's profile details including avatar upload.
     */
    public function updateProfile(Request $request)
    {
        $teacher = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้วในระบบ',
            'password.min' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านยืนยันไม่ตรงกัน',
            'avatar.image' => 'ไฟล์ที่อัปโหลดต้องเป็นไฟล์รูปภาพเท่านั้น',
            'avatar.max' => 'ขนาดไฟล์รูปภาพห้ามเกิน 2MB',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ];

        // Process Avatar image upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($teacher->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($teacher->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        // Process password update
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        // Update teacher user fields
        \App\Models\User::where('id', $teacher->id)->update($data);

        return redirect()->back()->with('success', 'ปรับปรุงข้อมูลส่วนตัวของคุณเรียบร้อยแล้ว');
    }
}
