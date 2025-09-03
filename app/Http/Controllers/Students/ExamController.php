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

    public function index(){

        // ดึงข้อมูล Level และ STD_CODE ของนักเรียนที่ล็อกอินอยู่
        // ส่วนนี้คงเดิมตามโค้ดของคุณ
        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $this->std_code = DB::table("student{$this->lavel}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

        $all_semestry = DB::table("grade{$this->lavel}")->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $semestry = $all_semestry->first()->SEMESTRY;
        
        // ***************************************************************
        // ส่วนเดิม: ดึงข้อมูลแบบทดสอบทั้งหมด
        // ***************************************************************
        $quizzes = DB::table('quizzes')
                      ->leftJoin('users', 'quizzes.created_by', '=', 'users.id')
                      ->select(
                          'quizzes.*',
                          'users.name as created_by_name' // ดึงชื่อผู้สร้างเพื่อนำไปแสดงผล
                      )
                      ->get();

        // ***************************************************************
        // ส่วนที่เพิ่มเข้ามา: ดึงประวัติการทำแบบทดสอบของนักเรียนปัจจุบัน
        // ***************************************************************
        $userId = Auth::id(); // รับ ID ของผู้ใช้ที่ล็อกอินอยู่

        $quizAttemptsHistory = DB::table('quiz_attempts')
                                ->where('quiz_attempts.user_id', $userId) // ใช้ alias หรือชื่อตารางที่ชัดเจน
                                ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                                ->select(
                                    'quiz_attempts.id as attempt_id',
                                    'quiz_attempts.total_score as user_score', // คะแนนที่นักเรียนทำได้
                                    'quiz_attempts.finished_at',
                                    'quizzes.title as quiz_title', // ชื่อแบบทดสอบ
                                    'quizzes.total_score as quiz_total_score', // คะแนนเต็มของแบบทดสอบ
                                    'quizzes.subject_code as subject_code' // รหัสวิชา
                                )
                                ->orderBy('quiz_attempts.finished_at', 'desc') // เรียงลำดับจากล่าสุดไปเก่าสุด
                                ->get();

        // ส่งข้อมูลทั้งหมดไปยัง View
        return view('students.exam', compact('quizzes', 'quizAttemptsHistory', 'all_semestry', 'semestry'));
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
    public function submitQuiz(Request $request, $quizId)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|integer|exists:questions,id',
            'questions.*.question_type' => 'required|string|in:multiple_choice,true_false,short_answer',
            // 'questions.*.answer' => 'nullable|string', // หรือใช้กฎที่เฉพาะเจาะจงมากขึ้น
        ]);

        $userId = Auth::id(); // ผู้ใช้ที่กำลังทำข้อสอบ

        // เริ่มต้น Transaction
        DB::beginTransaction();

        try {
            // ดึงเวลาเริ่มต้นจาก session หรือใช้เวลาปัจจุบันหากไม่มี (ซึ่งตอนนี้ควรจะมีแล้ว)
            $startedAt = session('quiz_start_time_' . $quizId) ?? Carbon::now();

            // 1. บันทึกการพยายามทำข้อสอบ (quiz_attempts)
            $attemptId = DB::table('quiz_attempts')->insertGetId([
                'user_id' => $userId, 
                'quiz_id' => $quizId,
                'started_at' => $startedAt,
                'finished_at' => Carbon::now(),
                'total_score' => 0, // จะคำนวณในภายหลัง
            ]);

            $totalScoreEarned = 0; // คะแนนรวมที่ได้จากการทำข้อสอบครั้งนี้
            $answeredQuestionsCount = 0; // นับจำนวนข้อที่ผู้ใช้ตอบ

            // 2. วนลูปบันทึกคำตอบแต่ละข้อ (answers)
            foreach ($request->input('questions') as $index => $answerData) {
                $questionId = $answerData['question_id'];
                $questionType = $answerData['question_type'];
                $userAnswer = $answerData['answer'] ?? null; // คำตอบของผู้ใช้

                // ดึงข้อมูลคำถามและคะแนนของคำถามนั้นจากฐานข้อมูล
                $question = DB::table('questions')->where('id', $questionId)->first();
                if (!$question) {
                    throw new \Exception("Question with ID {$questionId} not found.");
                }

                $isCorrect = false;
                $scoreEarned = 0;
                $selectedChoiceId = null;
                $writtenAnswer = null;

                // ตรวจสอบว่าผู้ใช้ได้ตอบคำถามข้อนี้หรือไม่ (ไม่ว่างเปล่า)
                if (!is_null($userAnswer) && (is_string($userAnswer) ? trim($userAnswer) !== '' : true)) {
                    $answeredQuestionsCount++;
                }

                if ($questionType === 'multiple_choice') {
                    $correctChoice = DB::table('choices')
                                        ->where('question_id', $questionId)
                                        ->where('is_correct', 1)
                                        ->value('id');

                    if ($userAnswer && (int)$userAnswer === (int)$correctChoice) {
                        $isCorrect = true;
                        $scoreEarned = $question->score;
                    }
                    $selectedChoiceId = $userAnswer;
                    $writtenAnswer = null;

                } elseif ($questionType === 'true_false') {
                    // **โปรดปรับ logic นี้ให้ตรงกับวิธีเก็บคำตอบที่ถูกต้องของ True/False ของคุณ**
                    // ตัวอย่าง: ถ้าคำถามนี้มีคำตอบที่ถูกต้องคือ '1' (ถูก)
                    // if ($userAnswer !== null && (int)$userAnswer === (int)$question->true_false_correct_value) { ... }
                    
                    if ($userAnswer !== null && (int)$userAnswer === 1 /* && <replace_with_your_true_false_correct_value_check> */) {
                        $isCorrect = true;
                        $scoreEarned = $question->score;
                    }
                    $selectedChoiceId = null;
                    $writtenAnswer = $userAnswer === '1' ? 'True' : ($userAnswer === '0' ? 'False' : null);

                } elseif ($questionType === 'short_answer') {
                    $isCorrect = false; // ต้องตรวจด้วยมือ
                    $scoreEarned = 0;
                    $selectedChoiceId = null;
                    $writtenAnswer = $userAnswer;
                }

                // บันทึกคำตอบ
                DB::table('answers')->insert([
                    'attempt_id' => $attemptId,
                    'question_id' => $questionId,
                    'selected_choice_id' => $selectedChoiceId,
                    'written_answer' => $writtenAnswer,
                    'is_correct' => $isCorrect,
                    'score' => $scoreEarned,
                ]);

                $totalScoreEarned += $scoreEarned;
            }

            // 3. อัปเดตคะแนนรวมใน quiz_attempts
            DB::table('quiz_attempts')->where('id', $attemptId)->update([
                'total_score' => $totalScoreEarned,
                'finished_at' => Carbon::now(),
            ]);

            // Commit Transaction หากสำเร็จ
            DB::commit();

            // 4. ดึงข้อมูล quiz_attempts และข้อมูลที่เกี่ยวข้องทั้งหมดสำหรับ Modal
            $attempt = DB::table('quiz_attempts')
                          ->where('id', $attemptId)
                          ->first();

            // ดึงข้อมูล Quiz และ Subject ที่เกี่ยวข้อง
            $quiz = DB::table('quizzes')
                      ->where('id', $attempt->quiz_id)
                      ->first();

            $id = auth()->user()->student_id;
            $lavel = str_split($id, 1)[3];
            $subject = null;
            $tableName = "subject".$lavel;

            if ($quiz->subject_code) { // ตรวจสอบว่ามี subject_code หรือไม่
                $subject = DB::table($tableName)
                              ->where('SUB_CODE', $quiz->subject_code)
                              ->first();
            }

            // คำนวณเวลาที่ใช้ไป
            $timeTaken = 'ไม่ระบุ';
            if ($attempt->started_at && $attempt->finished_at) {
                $start = Carbon::parse($attempt->started_at);
                $end = Carbon::parse($attempt->finished_at);
                $diffInSeconds = $end->diffInSeconds($start);
                $minutes = floor($diffInSeconds / 60);
                $seconds = $diffInSeconds % 60;
                $timeTaken = sprintf('%02d นาที %02d วินาที', $minutes, $seconds);
            }

            // นับจำนวนคำถามทั้งหมดในแบบทดสอบ
            $totalQuestionsInQuiz = DB::table('questions')
                                      ->where('quiz_id', $quizId)
                                      ->count();

            // เตรียมข้อมูลสำหรับ Modal
            $attemptDetails = [
                'quizTitle' => $quiz->title,
                'score' => $attempt->total_score,
                'totalQuizScore' => $quiz->total_score, // คะแนนเต็มของแบบทดสอบ (จากตาราง quizzes)
                'answeredQuestions' => $answeredQuestionsCount, // จำนวนข้อที่ผู้ใช้ตอบจริงๆ
                'totalQuestions' => $totalQuestionsInQuiz, // จำนวนข้อทั้งหมดในแบบทดสอบ
                'timeTaken' => $timeTaken,
                'subjectName' => $subject ? $subject->SUB_NAME : 'ไม่ระบุ', // ใช้จาก $subject
                'subjectCode' => $subject ? $subject->SUB_CODE : 'ไม่ระบุ', // ใช้จาก $subject
                'attemptDate' => Carbon::parse($attempt->finished_at)->translatedFormat('j F Y, H:i'),
            ];

            // ลบเวลาเริ่มต้นออกจาก session หลังจากทำเสร็จ
            $request->session()->forget('quiz_start_time_' . $quizId);

            // ส่งข้อมูลรายละเอียดการทำแบบทดสอบและข้อความสำเร็จกลับไป
            // ใช้ route students.exams.index เพื่อความสอดคล้องกับเมธอด index
            return redirect()->route('ทดสอบออนไลน์') 
                             ->with('success', 'ส่งแบบทดสอบสำเร็จ!')
                             ->with('attempt_details', $attemptDetails);

        } catch (\Exception $e) {
            // Rollback หากเกิดข้อผิดพลาด
            DB::rollBack();
            // Log error
            Log::error('Error submitting quiz: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการส่งแบบทดสอบ: ' . $e->getMessage())->withInput();
        }
    }
}