<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TeachersTestController extends Controller
{

    public function index()
    {
        $quizzes = DB::table('quizzes')
            ->where('created_by', auth()->id())
            ->select('quizzes.*')
            ->addSelect([
                // 1. นับจำนวนข้อสอบ (เหมือนเดิม)
                'questions_count' => DB::table('questions')
                    ->selectRaw('count(*)')
                    ->whereColumn('quiz_id', 'quizzes.id'),
                
                // 2. นับจำนวน "คน" ที่เข้าสอบทั้งหมด (นับรายคน ไม่นับครั้งซ้ำ)
                'unique_students_count' => DB::table('quiz_attempts')
                    ->selectRaw('count(distinct user_id)') 
                    ->whereColumn('quiz_id', 'quizzes.id'),
                    
                // 3. นับจำนวน "คน" ที่สอบผ่าน (นับเฉพาะคนที่เคยผ่านอย่างน้อย 1 ครั้ง)
                'unique_passed_count' => DB::table('quiz_attempts')
                    ->selectRaw('count(distinct user_id)')
                    ->whereColumn('quiz_id', 'quizzes.id')
                    ->where('is_passed', 1),
                    
                // 4. คำนวณร้อยละจาก "จำนวนคน" (Passed People / Total People * 100)
                'pass_rate' => DB::table('quiz_attempts')
                    ->selectRaw('
                        IF(count(distinct user_id) > 0, 
                        (count(distinct IF(is_passed = 1, user_id, NULL)) / count(distinct user_id)) * 100, 
                        0)
                    ')
                    ->whereColumn('quiz_id', 'quizzes.id'),
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teachers.ttest', compact('quizzes'));
    }

    public function create() { return view('teachers.create-ttest'); }

    public function store(Request $request)
    {
        $request->validate([
            'quiz_title' => 'required|string|max:255',
            'subject_code' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.score' => 'required|numeric|min:0',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'grade_level' => 'required|numeric|min:0|max:3',
        ]);

        DB::beginTransaction();
        try {
            // --- 1. Logic การจัดการภาพ Cover (ใช้แนวทางเดียวกับ initializeAttempt) ---
            $coverPublicUrl = null;
            if ($request->input('quiz_cover_base64')) {
                $imageData = $request->input('quiz_cover_base64');
                
                // ลบ prefix data:image/... ออกด้วย regex
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = base64_decode($imageData);

                // ตั้งชื่อไฟล์ (ใช้ชื่อวิชาและเวลา)
                $imageName = 'cover_' . $request->subject_code . '_' . time() . '.png';
                $directory = public_path('storage/images/exams/cover');
                
                // สร้าง Folder ถ้ายังไม่มี
                if (!file_exists($directory)) { 
                    mkdir($directory, 0777, true); 
                }

                // บันทึกไฟล์ลงใน path
                file_put_contents($directory . '/' . $imageName, $imageData);
                
                // สร้าง URL สำหรับเก็บลง Database (ใช้ asset() เพื่อให้ดึงรูปมาแสดงผลได้ง่าย)
                $coverPublicUrl = asset('storage/images/exams/cover/' . $imageName);
            }

            $totalQuizScore = 0;
            $requireLocation = $request->has('require_location') ? 1 : 0;
            $requireSnapshot = $request->has('require_snapshot') ? 1 : 0;

            // --- 2. บันทึก Attempt ลง Database (Quizzes Table) ---
            $quizId = DB::table('quizzes')->insertGetId([
                'title' => $request->quiz_title,
                'description' => $request->quiz_description,
                'subject_code' => $request->subject_code,
                'subject_group' => $request->subject_group,
                'pass_percentage' => $request->pass_percentage,
                'grade_level' => $request->grade_level,
                'require_location' => $requireLocation,
                'require_snapshot' => $requireSnapshot,
                'total_score' => 0,
                'time_limit' => $request->time_limit ?? 0,
                'cover_image' => $coverPublicUrl, // บันทึก URL รูปภาพปก
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- 3. บันทึกข้อสอบ (Logic เดิมของคุณ) ---
            foreach ($request->questions as $qData) {
                $questionId = DB::table('questions')->insertGetId([
                    'quiz_id' => $quizId,
                    'question_text' => $qData['question_text'],
                    'question_type' => $qData['question_type'],
                    'score' => $qData['score'],
                    'standard' => $qData['standard'] ?? null,
                    'indicator' => $qData['indicator'] ?? null,
                    'topic' => $qData['topic'] ?? null,
                    'taxonomy_level' => $qData['taxonomy_level'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalQuizScore += $qData['score'];

                if (isset($qData['choices'])) {
                    foreach ($qData['choices'] as $cData) {
                        if(!empty($cData['choice_text'])) {
                            DB::table('choices')->insert([
                                'question_id' => $questionId,
                                'choice_text' => $cData['choice_text'],
                                'is_correct' => isset($cData['is_correct']) ? 1 : 0,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // อัพเดทคะแนนรวมทั้งหมด
            DB::table('quizzes')->where('id', $quizId)->update(['total_score' => $totalQuizScore]);

            DB::commit();
            return redirect()->route('ttest.index')->with('success', 'สร้างแบบทดสอบและอัปโหลดภาพปกสำเร็จแล้ว!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $quiz = DB::table('quizzes')
                ->where('id', $id)
                ->where('created_by', Auth::id())
                ->first();

        if (!$quiz) {
            return redirect()->route('ttest.index')->with('error', 'ไม่พบแบบทดสอบที่ต้องการแก้ไข');
        }

        $questions = DB::table('questions')
                    ->where('quiz_id', $id)
                    ->get()
                    ->map(function($question) {
                        $question->choices = DB::table('choices')
                                                ->where('question_id', $question->id)
                                                ->get();
                        return $question;
                    });

        return view('teachers.edit-ttest', compact('quiz', 'questions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quiz_title' => 'required|string|max:255',
            'subject_code' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'grade_level' => 'required|numeric|min:0|max:3',
        ]);

        DB::beginTransaction();
        try {
            // --- 1. จัดการรูปภาพปก (Logic เดียวกับ initializeAttempt) ---
            $updateData = [
                'title' => $request->quiz_title,
                'subject_code' => $request->subject_code,
                'subject_group' => $request->subject_group,
                'pass_percentage' => $request->pass_percentage,
                'require_location' => $request->has('require_location') ? 1 : 0,
                'require_snapshot' => $request->has('require_snapshot') ? 1 : 0,
                'grade_level' => $request->grade_level,
                'time_limit' => $request->time_limit,
                'updated_at' => now(),
            ];

            if ($request->input('quiz_cover_base64')) {
                $imageData = $request->input('quiz_cover_base64');
                
                // ลบ prefix data:image/... ออกด้วย regex
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = base64_decode($imageData);

                // ตั้งชื่อไฟล์ (ใช้ ID และเวลาเพื่อความแม่นยำ)
                $imageName = 'cover_' . $id . '_' . time() . '.png';
                $directory = public_path('storage/images/quizzes/covers');
                
                if (!file_exists($directory)) { 
                    mkdir($directory, 0777, true); 
                }

                file_put_contents($directory . '/' . $imageName, $imageData);
                
                // เพิ่ม Path รูปภาพใหม่ลงใน array ที่จะ update
                $updateData['cover_image'] = asset('storage/images/quizzes/covers/' . $imageName);
            }

            // --- 2. อัปเดตข้อมูล Quiz ---
            DB::table('quizzes')->where('id', $id)->update($updateData);

            // --- 3. จัดการคำถามและตัวเลือก (Logic เดิม) ---
            $oldQuestionIds = DB::table('questions')->where('quiz_id', $id)->pluck('id');
            DB::table('choices')->whereIn('question_id', $oldQuestionIds)->delete();
            DB::table('questions')->where('quiz_id', $id)->delete();

            $totalScore = 0;
            foreach ($request->questions as $qData) {
                $questionId = DB::table('questions')->insertGetId([
                    'quiz_id' => $id,
                    'question_text' => $qData['question_text'],
                    'question_type' => $qData['question_type'] ?? 'multiple_choice',
                    'score' => $qData['score'],
                    'standard' => $qData['standard'] ?? null,
                    'indicator' => $qData['indicator'] ?? null,
                    'topic' => $qData['topic'] ?? null,
                    'taxonomy_level' => $qData['taxonomy_level'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalScore += $qData['score'];

                if (isset($qData['choices'])) {
                    foreach ($qData['choices'] as $cData) {
                        if (!empty($cData['choice_text'])) {
                            DB::table('choices')->insert([
                                'question_id' => $questionId,
                                'choice_text' => $cData['choice_text'],
                                'is_correct' => isset($cData['is_correct']) ? 1 : 0,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // อัปเดตคะแนนรวมสุดท้าย
            DB::table('quizzes')->where('id', $id)->update(['total_score' => $totalScore]);

            DB::commit();
            return redirect()->route('ttest.index')->with('success', 'แก้ไขข้อมูลและอัปเดตภาพปกเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Quiz Error: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการแก้ไข: ' . $e->getMessage());
        }
    }

    public function getSubjects(Request $request) {
        $grade = $request->query('grade');
        $search = $request->query('search');
        if (!in_array($grade, ['1', '2', '3'])) return response()->json([]);
        
        try {
            $subjects = DB::table("subject" . $grade)
                ->select('SUB_CODE', 'SUB_NAME')
                ->where('SUB_CODE', 'LIKE', "%{$search}%")
                ->orWhere('SUB_NAME', 'LIKE', "%{$search}%")
                ->get();
            return response()->json($subjects);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function toggle($id) 
    {
        // ดึงค่าปัจจุบันออกมาก่อนเพื่อทำการสลับ (Toggle)
        $quiz = DB::table('quizzes')->where('id', $id)->first();

        if (!$quiz) {
            return back()->with('error', 'ไม่พบข้อมูลแบบทดสอบ');
        }

        // อัปเดตผ่าน DB Facade
        DB::table('quizzes')
            ->where('id', $id)
            ->update([
                'is_active' => !$quiz->is_active,
                'updated_at' => now() // อย่าลืมอัปเดตเวลาด้วยนะครับถ้าใช้ DB::
            ]);

        return back()->with('success', 'อัปเดตสถานะแบบทดสอบเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // 1. ลบคำตอบของผู้เข้าสอบก่อน (ถ้ามี)
                // สมมติว่าตารางชื่อ quiz_attempts
                DB::table('quiz_attempts')->where('quiz_id', $id)->delete();

                // 2. ลบข้อสอบในแบบทดสอบนั้น
                DB::table('questions')->where('quiz_id', $id)->delete();

                // 3. ลบตัวแบบทดสอบเอง
                DB::table('quizzes')
                    ->where('id', $id)
                    ->where('created_by', auth()->id()) // เช็คสิทธิ์ว่าเป็นเจ้าของไหม
                    ->delete();
            });

            return back()->with('success', 'ลบแบบทดสอบและข้อมูลที่เกี่ยวข้องเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return back()->with('error', 'ไม่สามารถลบได้: ' . $e->getMessage());
        }
    }

    // --- เพิ่มใหม่: แสดงหน้าจอมอบหมายข้อสอบ ---
    public function assignView($id)
    {
        // 1. ดึงข้อมูลข้อสอบ
        $quiz = DB::table('quizzes')->where('id', $id)->first();
        if (!$quiz) return back()->with('error', 'ไม่พบข้อสอบ');

        $lavel = $quiz->grade_level; // 1, 2, or 3
        $tgrade = "grade{$lavel}";
        $tstudent = "student{$lavel}";

        // 2. หาภาคเรียนล่าสุดจากตาราง grade (อิงตามระดับชั้นนั้น)
        $latestSemestry = DB::table($tgrade)->max('SEMESTRY');

        if (!$latestSemestry) {
            return back()->with('error', 'ไม่พบข้อมูลการลงทะเบียนในระดับชั้นนี้');
        }

        // 3. ดึงกลุ่มเรียน (ดึงจากตาราง group โดย Join กับ grade ของเทอมนั้น)
        $groups = DB::table($tgrade)
            ->join('group', 'group.GRP_CODE', '=', "$tgrade.GRP_CODE")
            ->where("$tgrade.SEMESTRY", $latestSemestry)
            ->select('group.GRP_CODE', 'group.GRP_NAME')
            ->distinct()
            ->orderBy('group.GRP_CODE', 'ASC')
            ->get();

        // 4. ดึงรายชื่อนักเรียน (Logic เดียวกับ current_student ของคุณ)
        // เพิ่มการ Join ตาราง users เพื่อเอา user_id ไปใช้บันทึกการมอบหมาย
        $students = DB::table($tgrade)
            ->where("$tgrade.SEMESTRY", $latestSemestry)
            ->join($tstudent, "$tgrade.STD_CODE", '=', "$tstudent.STD_CODE")
            ->join('users', "$tstudent.ID", '=', 'users.student_id') // เชื่อมเพื่อหา ID ในระบบ App
            ->select(
                'users.id as user_id', 
                "$tstudent.STD_CODE as student_code",
                "$tstudent.NAME", 
                "$tstudent.SURNAME",
                "$tstudent.GRP_CODE"
            )
            ->distinct()
            ->orderBy("$tstudent.STD_CODE", 'ASC')
            ->get();

        $gradeNames = [1 => 'ประถมศึกษา', 2 => 'มัธยมศึกษาตอนต้น', 3 => 'มัธยมศึกษาตอนปลาย'];
        $gradeLabel = $gradeNames[$lavel] ?? 'ไม่ระบุ';

        return view('teachers.assign-quiz', compact('quiz', 'students', 'groups', 'latestSemestry', 'gradeLabel'));
    }

    // --- เพิ่มใหม่: บันทึกการมอบหมายข้อสอบ ---
    public function assignStore(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array', // รายชื่อนักเรียนที่ถูกเลือก
            'due_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->user_ids as $studentId) {
                // ใช้ updateOrInsert เพื่อป้องกันการมอบหมายซ้ำ
                DB::table('quiz_assignments')->updateOrInsert(
                    ['quiz_id' => $id, 'user_id' => $studentId],
                    [
                        'assigned_by' => Auth::id(),
                        'assigned_at' => now(),
                        'due_date' => $request->due_date,
                        'is_completed' => 0
                    ]
                );
            }

            DB::commit();
            return redirect()->route('ttest.index')->with('success', 'มอบหมายข้อสอบให้ผู้เรียนเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function quizReport($id)
    {
        // 1. ดึงข้อมูลข้อสอบ (ตรวจสอบว่ามีฟิลด์ certification_image ในตาราง quizzes)
        $quiz = DB::table('quizzes')->where('id', $id)->first();

        // 2. ดึงข้อมูลดิบทั้งหมดมาก่อน
        $rawAttempts = DB::table('quiz_attempts')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->select(
                'quiz_attempts.*',
                'users.student_id',
                'users.name as user_name_backup'
            )
            ->where('quiz_attempts.quiz_id', $id)
            ->whereNotNull('quiz_attempts.finished_at')
            ->get();

        // 3. Group ตามรหัสนักเรียน แล้วเลือกเฉพาะครั้งที่คะแนนเยอะที่สุด
        $attempts = $rawAttempts->groupBy('student_id')->map(function ($group) {
            $bestAttempt = $group->sortByDesc(function ($attempt) {
                return sprintf('%09d%s', $attempt->total_score, $attempt->finished_at);
            })->first();

            $bestAttempt->attempt_count = $group->count();
            
            $start = \Carbon\Carbon::parse($bestAttempt->started_at);
            $end = \Carbon\Carbon::parse($bestAttempt->finished_at);
            $bestAttempt->duration_text = $start->diff($end)->format('%H:%I:%S');
            
            return $bestAttempt;
        })->values();

        // 4. ดึงชื่อจริงจากตาราง student1, 2, 3
        $studentGroups = ['1' => [], '2' => [], '3' => []];
        foreach ($attempts as $attempt) {
            $sid = trim($attempt->student_id);
            if (strlen($sid) >= 4) {
                $gradeDigit = substr($sid, 3, 1);
                if (isset($studentGroups[$gradeDigit])) $studentGroups[$gradeDigit][] = $sid;
            }
        }

        $studentInfoMap = [];
        foreach ($studentGroups as $grade => $ids) {
            if (!empty($ids)) {
                $students = DB::table("student{$grade}")->whereIn('ID', $ids)->get();
                foreach ($students as $s) {
                    $fullName = ($s->PRENAME ?? '') . ($s->NAME ?? '') . ' ' . ($s->SURNAME ?? '');
                    $studentInfoMap[$s->ID] = $fullName;
                }
            }
        }

        // 5. Map ชื่อจริงกลับเข้าไป
        foreach ($attempts as $attempt) {
            $attempt->full_name = $studentInfoMap[trim($attempt->student_id)] ?? $attempt->user_name_backup;
        }

        $attempts = $attempts->sortByDesc('total_score');

        return view('teachers.quiz_summary', compact('quiz', 'attempts'));
    }


public function getCertificateBase64(Request $request) {
    $url = $request->query('url');

    if (!$url) {
        return response()->json(['error' => 'URL is required'], 400);
    }

    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ข้ามการเช็ค SSL ถ้าใบเซอร์มีปัญหา
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // ติดตามถ้ามีการ Redirect
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);           // รอได้นานขึ้น
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if ($httpCode !== 200 || !$data) {
            return response()->json([
                'error' => "ดึงรูปไม่สำเร็จ (HTTP $httpCode)",
                'url_attempted' => $url
            ], 500);
        }

        $base64 = 'data:' . $contentType . ';base64,' . base64_encode($data);
        return response()->json(['base64' => $base64]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}