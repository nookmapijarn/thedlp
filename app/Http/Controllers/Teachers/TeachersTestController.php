<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // อย่าลืม use ด้านบนไฟล์

class TeachersTestController extends Controller
{

    public function index()
    {
        $quizzes = DB::table('quizzes')
            ->where('created_by', auth()->id())
            ->select('quizzes.*')
            ->addSelect([
                // 1. นับจำนวนข้อสอบ
                'questions_count' => DB::table('questions')
                    ->selectRaw('count(*)')
                    ->whereColumn('quiz_id', 'quizzes.id'),
                
                // 2. นับจำนวน "คน" ที่เข้าสอบทั้งหมด
                'unique_students_count' => DB::table('quiz_attempts')
                    ->selectRaw('count(distinct user_id)') 
                    ->whereColumn('quiz_id', 'quizzes.id'),
                    
                // 3. นับจำนวน "คน" ที่สอบผ่าน
                'unique_passed_count' => DB::table('quiz_attempts')
                    ->selectRaw('count(distinct user_id)')
                    ->whereColumn('quiz_id', 'quizzes.id')
                    ->where('is_passed', 1),
                    
                // 4. คำนวณร้อยละ
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
            // --- 1. เตรียมตัวแปรสำหรับ URL รูปภาพ (ประกาศไว้ข้างนอก if เสมอ) ---
            $coverPublicUrl = null;
            $certPublicUrl = null; // *** แก้ไขจุดนี้: เพิ่มการประกาศตัวแปรเริ่มต้น ***

            // --- 2. Logic การจัดการภาพ Cover ---
            if ($request->input('quiz_cover_base64')) {
                $imageData = $request->input('quiz_cover_base64');
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = base64_decode($imageData);

                $imageName = 'cover_' . $request->subject_code . '_' . time() . '.png';
                $directory = public_path('storage/images/exams/cover');
                
                if (!file_exists($directory)) { 
                    mkdir($directory, 0777, true); 
                }

                file_put_contents($directory . '/' . $imageName, $imageData);
                $coverPublicUrl = asset('storage/images/exams/cover/' . $imageName);
            }

            // --- 3. จัดการภาพเกียรติบัตร (Base64) ---
            if ($request->input('quiz_certificate_base64')) {
                $certData = $request->input('quiz_certificate_base64');
                $certData = preg_replace('#^data:image/\w+;base64,#i', '', $certData);
                $certData = base64_decode($certData);

                $certName = 'cert_' . $request->subject_code . '_' . time() . '.png';
                $certDirectory = public_path('storage/images/exams/certificate');
                
                if (!file_exists($certDirectory)) { 
                    mkdir($certDirectory, 0777, true); 
                }

                file_put_contents($certDirectory . '/' . $certName, $certData);
                $certPublicUrl = asset('storage/images/exams/certificate/' . $certName);
            }

            $totalQuizScore = 0;
            $requireLocation = $request->has('require_location') ? 1 : 0;
            $requireSnapshot = $request->has('require_snapshot') ? 1 : 0;

            // --- 4. บันทึก Quiz ลง Database ---
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
                'cover_image' => $coverPublicUrl,
                'certificate_image' => $certPublicUrl, // ตอนนี้จะไม่ Error แล้วเพราะมีค่า null รองรับ
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- 5. บันทึกข้อสอบและ Choice ---
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
                    foreach ($qData['choices'] as $cIdx => $cData) {
                        if(!empty($cData['choice_text'])) {
                            $isCorrect = (isset($qData['correct_index']) && $qData['correct_index'] == $cIdx) ? 1 : 0;

                            DB::table('choices')->insert([
                                'question_id' => $questionId,
                                'choice_text' => $cData['choice_text'],
                                'is_correct' => $isCorrect,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // อัพเดทคะแนนรวมทั้งหมด
            DB::table('quizzes')->where('id', $quizId)->update(['total_score' => $totalQuizScore]);

            DB::commit();
            return redirect()->route('ttest.index')->with('success', 'สร้างแบบทดสอบเรียบร้อยแล้ว!');

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
                                        ->get()
                                        ->map(function($choice) {
                                            // *** แก้ไขจุดที่ 3: แปลงเป็น Boolean เพื่อให้ JS ทำงานได้ถูกต้อง ***
                                            $choice->is_correct = (bool)$choice->is_correct; 
                                            return $choice;
                                        });
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
            'questions.*.score' => 'required|numeric|min:0',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'grade_level' => 'required|numeric|min:0|max:3',
        ]);

        DB::beginTransaction();
        try {
            // ดึงข้อมูลเดิมมาเพื่อตรวจสอบไฟล์ภาพเก่า
            $quiz = DB::table('quizzes')->where('id', $id)->first();
            if (!$quiz) {
                throw new \Exception("ไม่พบข้อมูลแบบทดสอบ");
            }

            $updateData = [
                'title' => $request->quiz_title,
                'description' => $request->quiz_description,
                'subject_code' => $request->subject_code,
                'subject_group' => $request->subject_group,
                'pass_percentage' => $request->pass_percentage,
                'grade_level' => $request->grade_level,
                'require_location' => $request->has('require_location') ? 1 : 0,
                'require_snapshot' => $request->has('require_snapshot') ? 1 : 0,
                'time_limit' => $request->time_limit ?? 0,
                'updated_at' => now(),
            ];

            // --- 1. จัดการภาพ Cover ---
            $coverBase64 = $request->input('quiz_cover_base64');
            if ($coverBase64 === 'REMOVE') {
                $this->deleteOldFile($quiz->cover_image); // ลบไฟล์เก่า
                $updateData['cover_image'] = null;
            } elseif ($coverBase64 && str_starts_with($coverBase64, 'data:image')) {
                $this->deleteOldFile($quiz->cover_image); // ลบไฟล์เก่าก่อนลงไฟล์ใหม่
                
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $coverBase64);
                $imageName = 'cover_' . $request->subject_code . '_' . time() . '.png';
                $directory = public_path('storage/images/exams/cover');
                
                if (!File::exists($directory)) { File::makeDirectory($directory, 0777, true); }
                File::put($directory . '/' . $imageName, base64_decode($imageData));
                
                $updateData['cover_image'] = asset('storage/images/exams/cover/' . $imageName);
            }

            // --- 2. จัดการภาพ Certificate ---
            $certBase64 = $request->input('quiz_certificate_base64');
            if ($certBase64 === 'REMOVE') {
                $this->deleteOldFile($quiz->certificate_image);
                $updateData['certificate_image'] = null;
            } elseif ($certBase64 && str_starts_with($certBase64, 'data:image')) {
                $this->deleteOldFile($quiz->certificate_image);
                
                $certData = preg_replace('#^data:image/\w+;base64,#i', '', $certBase64);
                $certName = 'cert_' . $request->subject_code . '_' . time() . '.png';
                $certDirectory = public_path('storage/images/exams/certificate');
                
                if (!File::exists($certDirectory)) { File::makeDirectory($certDirectory, 0777, true); }
                File::put($certDirectory . '/' . $certName, base64_decode($certData));
                
                $updateData['certificate_image'] = asset('storage/images/exams/certificate/' . $certName);
            }

            // --- 3. อัปเดตข้อมูล Quiz หลัก ---
            DB::table('quizzes')->where('id', $id)->update($updateData);

            // --- 4. จัดการคำถามและตัวเลือก (Delete and Re-insert) ---
            $oldQuestionIds = DB::table('questions')->where('quiz_id', $id)->pluck('id');
            DB::table('choices')->whereIn('question_id', $oldQuestionIds)->delete();
            DB::table('questions')->where('quiz_id', $id)->delete();

            $totalQuizScore = 0;
            foreach ($request->questions as $qData) {
                $questionId = DB::table('questions')->insertGetId([
                    'quiz_id' => $id,
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
                    foreach ($qData['choices'] as $cIdx => $cData) {
                        if(!empty($cData['choice_text'])) {
                            $isCorrect = (isset($qData['correct_index']) && $qData['correct_index'] == $cIdx) ? 1 : 0;
                            DB::table('choices')->insert([
                                'question_id' => $questionId,
                                'choice_text' => $cData['choice_text'],
                                'is_correct' => $isCorrect,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // อัปเดตคะแนนรวมสุดท้าย
            DB::table('quizzes')->where('id', $id)->update(['total_score' => $totalQuizScore]);

            DB::commit();
            return redirect()->route('ttest.index')->with('success', 'อัปเดตแบบทดสอบเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Quiz Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper function สำหรับลบไฟล์ภาพเก่า
     */
    private function deleteOldFile($fullUrl)
    {
        if (!$fullUrl) return;

        // แปลง URL กลับเป็น Path ในเครื่อง (สมมติว่าใช้ asset('storage/...'))
        $path = str_replace(asset('storage'), public_path('storage'), $fullUrl);
        
        if (File::exists($path)) {
            File::delete($path);
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
        $quiz = DB::table('quizzes')->where('id', $id)->first();

        if (!$quiz) {
            return back()->with('error', 'ไม่พบข้อมูลแบบทดสอบ');
        }

        DB::table('quizzes')
            ->where('id', $id)
            ->update([
                'is_active' => !$quiz->is_active,
                'updated_at' => now()
            ]);

        return back()->with('success', 'อัปเดตสถานะแบบทดสอบเรียบร้อยแล้ว');
    }

    /**
     * แก้ไขฟังก์ชัน destroy เดิม: ลบ "ทั้งแบบทดสอบ"
     * เพิ่มการลบไฟล์รูปภาพปกเพื่อไม่ให้หนักเครื่อง
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $quiz = DB::table('quizzes')->where('id', $id)->first();
                
                // ลบไฟล์ภาพปกจาก Folder (ถ้ามี)
                if ($quiz && $quiz->cover_image) {
                    $imagePath = str_replace(asset(''), public_path(''), $quiz->cover_image);
                    if (file_exists($imagePath)) { @unlink($imagePath); }
                }

                // ลบข้อมูลที่เกี่ยวข้องตามลำดับ (Foreign Key)
                DB::table('quiz_attempts')->where('quiz_id', $id)->delete();
                
                $questionIds = DB::table('questions')->where('quiz_id', $id)->pluck('id');
                DB::table('choices')->whereIn('question_id', $questionIds)->delete();
                
                DB::table('questions')->where('quiz_id', $id)->delete();
                DB::table('quiz_assignments')->where('quiz_id', $id)->delete();
                
                DB::table('quizzes')
                    ->where('id', $id)
                    ->where('created_by', auth()->id())
                    ->delete();
            });

            return redirect()->route('ttest.index')->with('success', 'ลบแบบทดสอบและข้อมูลที่เกี่ยวข้องทั้งหมดเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            Log::error('Destroy Quiz Error: ' . $e->getMessage());
            return back()->with('error', 'ไม่สามารถลบได้: ' . $e->getMessage());
        }
    }

    public function assignView($id)
    {
        $quiz = DB::table('quizzes')->where('id', $id)->first();
        if (!$quiz) return back()->with('error', 'ไม่พบข้อสอบ');

        $level = $quiz->grade_level;
        $levelsToFetch = ($level == 0) ? [1, 2, 3] : [$level];
        
        $allGroups = collect();
        $allStudents = collect();
        $latestSemestry = null;

        foreach ($levelsToFetch as $lvl) {
            $tgrade = "grade{$lvl}";
            $tstudent = "student{$lvl}";

            $currentLatestSem = DB::table($tgrade)->max('SEMESTRY');
            if (!$currentLatestSem) continue;
            
            $latestSemestry = $currentLatestSem;

            $groups = DB::table($tgrade)
                ->join('group', 'group.GRP_CODE', '=', "$tgrade.GRP_CODE")
                ->where("$tgrade.SEMESTRY", $currentLatestSem)
                ->select('group.GRP_CODE', 'group.GRP_NAME')
                ->distinct()
                ->get();
            $allGroups = $allGroups->merge($groups);

            $students = DB::table($tgrade)
                ->where("$tgrade.SEMESTRY", $currentLatestSem)
                ->join($tstudent, "$tgrade.STD_CODE", '=', "$tstudent.STD_CODE")
                ->join('users', "$tstudent.ID", '=', 'users.student_id')
                ->select(
                    'users.id as user_id', 
                    "$tstudent.ID as student_id",
                    "$tstudent.NAME", 
                    "$tstudent.SURNAME",
                    "$tstudent.GRP_CODE",
                )
                ->distinct()
                ->get();
            $allStudents = $allStudents->merge($students);
        }

        if ($allStudents->isEmpty()) {
            return back()->with('error', 'ไม่พบข้อมูลนักเรียนในระดับชั้นที่เลือก');
        }

        $groups = $allGroups->unique('GRP_CODE')->sortBy('GRP_CODE');
        $students = $allStudents->sortBy('student_code');

        $gradeNames = [
            0 => 'ทุกระดับชั้น',
            1 => 'ประถมศึกษา', 
            2 => 'มัธยมศึกษาตอนต้น', 
            3 => 'มัธยมศึกษาตอนปลาย'
        ];
        $gradeLabel = $gradeNames[$level] ?? 'ไม่ระบุ';

        return view('teachers.assign-quiz', compact('quiz', 'students', 'groups', 'latestSemestry', 'gradeLabel'));
    }

    public function assignStore(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'due_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->user_ids as $studentId) {
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
        $quiz = DB::table('quizzes')->where('id', $id)->first();

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
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
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

/**
     * ฟังก์ชันลบคำตอบทั้งหมดของนักเรียน 1 คน ในแบบทดสอบที่กำหนด
     */
    public function deleteStudentAttempts(Request $request)
    {
        $quizId = $request->quiz_id;
        $userId = $request->user_id;

        try {
            DB::beginTransaction();

            // 1. ค้นหาข้อมูลการสอบทั้งหมดของคนนี้ใน Quiz นี้
            $attempts = DB::table('quiz_attempts')
                ->where('quiz_id', $quizId)
                ->where('user_id', $userId)
                ->get();

            if ($attempts->isEmpty()) {
                return back()->with('error', 'ไม่พบข้อมูลที่ต้องการลบ');
            }

            // 2. ลบรูปถ่ายหลักฐาน (ถ้ามีการเก็บไฟล์ใน Server)
            foreach ($attempts as $attempt) {
                if ($attempt->start_photo) {
                    // ปรับตามโครงสร้างการเก็บไฟล์ของคุณ เช่น storage_path หรือ public_path
                    $path = str_replace(asset('storage'), public_path('storage'), $attempt->start_photo);
                    if (file_exists($path)) { @unlink($path); }
                }
            }

            // 3. ลบประวัติการสอบทั้งหมดของคนนี้ใน Quiz นี้
            DB::table('quiz_attempts')
                ->where('quiz_id', $quizId)
                ->where('user_id', $userId)
                ->delete();

            // 4. รีเซ็ตสถานะการมอบหมายงานเพื่อให้เด็กกลับมาสอบใหม่ได้ (ถ้ามีระบบ Assignment)
            DB::table('quiz_assignments')
                ->where('quiz_id', $quizId)
                ->where('user_id', $userId)
                ->update([
                    'is_completed' => 0
                ]);

            DB::commit();
            return back()->with('success', 'ล้างประวัติการสอบทั้งหมดของนักเรียนเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete Student Attempts Error: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}