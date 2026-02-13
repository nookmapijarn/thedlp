<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // เพิ่ม Auth facade เพื่อดึงข้อมูลผู้ใช้ที่ล็อกอิน
use Carbon\Carbon; // *** เพิ่มการ import Carbon Facade ที่นี่ ***
use Illuminate\Support\Facades\Log; // เพิ่มการ import Log Facade สำหรับการบันทึก error

class ExamController extends Controller
{
    protected $lavel;
    protected $std_code;

    public function index(Request $request)
    {
        // 1. ดึงข้อมูลพื้นฐานของนักเรียน (ส่วนเดิม)
        $id = auth()->user()->student_id;
        $userId = auth()->id(); // ID จากตาราง users
        $this->lavel = str_split($id, 1)[3];
        $this->std_code = DB::table("student{$this->lavel}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

        $all_semestry = DB::table("grade{$this->lavel}")->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $semestry = $all_semestry->first() ? $all_semestry->first()->SEMESTRY : null;

        // 2. ดึงข้อมูล "งานที่ได้รับมอบหมาย" (Assigned Quizzes)
        // ดึงเฉพาะที่ครูสั่งให้ User นี้ทำผ่านตาราง quiz_assignments
        $assignedQuizzes = DB::table('quiz_assignments')
            ->join('quizzes', 'quiz_assignments.quiz_id', '=', 'quizzes.id')
            ->leftJoin('users', 'quizzes.created_by', '=', 'users.id')
            ->where('quiz_assignments.user_id', $userId)
            ->select(
                'quizzes.*',
                'users.name as created_by_name',
                'quiz_assignments.assigned_at',
                'quiz_assignments.due_date',
                'quiz_assignments.is_completed'
            )
            ->orderBy('quiz_assignments.is_completed', 'asc') // เอาที่ยังไม่ทำขึ้นก่อน
            ->orderBy('quiz_assignments.assigned_at', 'desc')
            ->get();

        // 3. สร้าง Query สำหรับ "คลังข้อสอบทั้งหมด" (All Quizzes)
        $query = DB::table('quizzes')
            ->leftJoin('users', 'quizzes.created_by', '=', 'users.id')
            ->select(
                'quizzes.*',
                'users.name as created_by_name'
            )
            // เพิ่มการเช็คว่า user นี้เคยทำข้อสอบนี้หรือยัง
            ->addSelect([
                'is_attempted' => DB::table('quiz_attempts')
                    ->selectRaw('count(*)')
                    ->whereColumn('quiz_id', 'quizzes.id')
                    ->where('user_id', $userId)
                    ->whereNotNull('finished_at') // ต้องทำเสร็จแล้วเท่านั้น
                    ->limit(1)
            ])
            ->where('quizzes.is_active', 1);

        // --- ระบบค้นหาและกรอง (Search & Filters) ---
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('quizzes.title', 'like', '%' . $request->search . '%')
                ->orWhere('quizzes.descriptio', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('subject')) {
            $query->where('quizzes.subject_table_type', $request->subject);
        }

        if ($request->filled('grade')) {
            $query->where('quizzes.grade_level', $request->grade);
        }

        if ($request->filled('creator')) {
            $query->where('quizzes.created_by', $request->creator);
        }

        // เรียงลำดับ
        $sortField = $request->get('sort', 'id'); 
        $sortOrder = $request->get('order', 'desc');
        $quizzes = $query->orderBy("quizzes.{$sortField}", $sortOrder)->get();

        // 4. ดึงข้อมูลสำหรับ Dropdown Filter
        $subjects = DB::table('quizzes')->where('subject_table_type', '!=', '')->distinct()->pluck('subject_table_type');
        $grades = DB::table('quizzes')->distinct()->pluck('grade_level')->sort();
        $creators = DB::table('users')
            ->whereIn('id', function($q) {
                $q->select('created_by')->from('quizzes');
            })
            ->select('id', 'name')
            ->get();

        // 5. ดึงประวัติการทำข้อสอบ (History)
        $quizAttemptsHistory = DB::table('quiz_attempts')
            ->where('quiz_attempts.user_id', $userId)
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->select(
                'quiz_attempts.id as attempt_id',
                'quiz_attempts.total_score as user_score',
                'quiz_attempts.finished_at',
                'quizzes.title as quiz_title',
                'quizzes.total_score as quiz_total_score',
                'quizzes.cover_image as cover_image',
                'quizzes.certificate_image as certificate_image',
            )
            ->orderBy('quiz_attempts.finished_at', 'desc')
            ->get();

        // 6. ส่งข้อมูลไปยัง View
        return view('students.exam', compact(
            'assignedQuizzes', // งานที่ครูสั่ง
            'quizzes',         // ข้อสอบทั้งหมด
            'quizAttemptsHistory', 
            'all_semestry', 
            'semestry',
            'subjects',
            'grades',
            'creators'
        ));
    }

    public function initializeAttempt(Request $request, $id)
    {
        try {
            $quiz = DB::table('quizzes')->where('id', $id)->first();
            if (!$quiz) {
                return response()->json(['success' => false, 'message' => 'ไม่พบแบบทดสอบ'], 404);
            }

            $publicUrl = null;

            // --- Logic: การถ่ายภาพ (require_snapshot) ---
            $quiz = DB::table('quizzes')->where('id', $id)->first();
            if ($quiz->require_snapshot == 1) {
                $imageData = $request->input('start_photo');
                if (!$imageData) {
                    return response()->json(['success' => false, 'message' => 'ต้องถ่ายภาพเพื่อยืนยันตัวตน'], 400);
                }

                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = base64_decode($imageData);

                $studentId = Auth::user()->student_id; 
                $imageName = $studentId . '_quiz_' . $id . '_' . time() . '.png';
                $directory = public_path('storage/images/exams/start_photo');
                if (!file_exists($directory)) { mkdir($directory, 0777, true); }

                file_put_contents($directory . '/' . $imageName, $imageData);
                $publicUrl = asset('storage/images/exams/start_photo' . $imageName);
            }

            // --- บันทึก Attempt ลง Database ---
            $attemptId = DB::table('quiz_attempts')->insertGetId([
                'user_id'    => Auth::id(),
                'quiz_id'    => $id,
                'started_at' => now(),
                'ip_address' => $request->ip(),
                // บันทึกพิกัดเฉพาะเมื่อ require_location เป็น 1
                'latitude'   => ($quiz->require_location == 1) ? $request->input('latitude') : null,
                'longitude'  => ($quiz->require_location == 1) ? $request->input('longitude') : null,
                'browser_fingerprint' => substr($request->header('User-Agent'), 0, 250),
                'start_photo' => $publicUrl,
                'tab_switch_count' => 0,
                'total_score' => 0,
                'is_passed'  => 0,
            ]);

            session()->put("active_attempt_{$id}", $attemptId);
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * แสดงหน้าทำแบบทดสอบสำหรับนักเรียน
     * @param int $quizId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function startQuiz($quizId)
    {
        $quiz = DB::table('quizzes')->where('id', $quizId)->first();

        if (!$quiz) {
            // ใช้ route students.exams.index เพื่อความสอดคล้องกับเมธอด index
            return redirect()->route('students.exams.index')->with('error', 'ไม่พบแบบทดสอบที่คุณต้องการทำ');
        }

        // *** เพิ่มโค้ดส่วนนี้เพื่อบันทึกเวลาเริ่มต้นทำข้อสอบใน Session ***
        session()->put('quiz_start_time_' . $quizId, Carbon::now());
        Log::info('Quiz started for user ' . Auth::id() . ' on quiz ' . $quizId . ' at ' . session('quiz_start_time_' . $quizId));
        // ***************************************************************

        // ดึงคำถามทั้งหมดสำหรับแบบทดสอบนี้
        $questions = DB::table('questions')
                        ->where('quiz_id', $quizId)
                        ->orderBy('id') // เรียงลำดับคำถาม
                        ->get();

        // ดึงตัวเลือกสำหรับคำถามปรนัย
        foreach ($questions as $question) {
            if ($question->question_type === 'multiple_choice') {
                $question->choices = DB::table('choices')
                                        ->where('question_id', $question->id)
                                        ->get();
            } else {
                $question->choices = collect(); // กำหนดเป็น Collection ว่างเพื่อป้องกัน error ใน blade
            }
        }

        return view('students.do_quiz', compact('quiz', 'questions'));
    }

    /**
     * จัดการการส่งคำตอบแบบทดสอบ
     * @param Request $request
     * @param int $quizId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitQuiz(Request $request, $id)
    {
        // dd($request->all());

        $attemptId = session("active_attempt_{$id}");

        if (!$attemptId) {
            return redirect()->route('ทดสอบออนไลน์')->with('error', 'ไม่พบประวัติการเริ่มสอบ หรือเซสชันหมดอายุ');
        }

        DB::beginTransaction();
        try {
            $totalScoreEarned = 0;
            $questionsData = $request->input('questions', []);

            foreach ($questionsData as $item) {
                $questionId = $item['question_id'];
                $userAnswer = $item['answer'] ?? null;

                // ดึงคำตอบที่ถูกต้องจาก Database
                $question = DB::table('questions')->where('id', $questionId)->first();

                if ($question) {
                    if ($question->question_type === 'multiple_choice') {
                        // เช็คจากตาราง choices ว่าข้อที่เลือก is_correct หรือไม่
                        $choice = DB::table('choices')->where('id', $userAnswer)->first();
                        if ($choice && $choice->is_correct) {
                            $totalScoreEarned += $question->score;
                        }
                    } 
                    elseif ($question->question_type === 'true_false') {
                        // เช็คค่า 0 หรือ 1 ตรงๆ
                        if ($userAnswer !== null && (int)$userAnswer === (int)$question->correct_answer) {
                            $totalScoreEarned += $question->score;
                        }
                    }
                    // กรณี short_answer คุณอาจต้องตรวจด้วยมือ (Manual Grade) หรือ Logic อื่น
                }
            }

            // ดึงเกณฑ์ผ่านจากตาราง quizzes
            $quiz = DB::table('quizzes')->where('id', $id)->first();
            $passScore = $quiz->pass_score ?? 0;
            $isPassed = ($totalScoreEarned >= $passScore) ? 1 : 0;

            // อัปเดตข้อมูลลงใน Record เดิม
            DB::table('quiz_attempts')->where('id', $attemptId)->update([
                'finished_at' => Carbon::now(),
                'total_score'  => $totalScoreEarned,
                'is_passed'   => $isPassed,
                'tab_switch_count'  => $request->input('tab_switches', 0),
            ]);

            DB::commit();
            
            // ล้าง Session เมื่อทำเสร็จ
            session()->forget("active_attempt_{$id}");

            return redirect()->route('ทดสอบออนไลน์')->with('success', "ส่งข้อสอบสำเร็จ! ได้คะแนน: $totalScoreEarned");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Submit Quiz Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการส่งข้อสอบ: ' . $e->getMessage());
        }
    }

    // ใน Controller ของคุณ (เช่น QuizController)
    public function getCertificateBase64(Request $request) {
        $url = $request->query('url');
        try {
            $imageData = file_get_contents($url);
            $type = pathinfo($url, PATHINFO_EXTENSION);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
            return response()->json(['base64' => $base64]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not load image'], 500);
        }
    }
}