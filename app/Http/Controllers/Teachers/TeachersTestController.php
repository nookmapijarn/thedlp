<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // เพิ่มการ import Log Facade สำหรับการบันทึก error

// หากคุณมี Eloquent Models สำหรับ Quiz, Question, Choice และ Subject
// คุณสามารถ uncomment บรรทัดเหล่านี้เพื่อใช้แทน DB::table() ได้
// use App\Models\Quiz;
// use App\Models\Question;
// use App\Models\Choice;
// use App\Models\Subject;

class TeachersTestController extends Controller
{
    /**
     * แสดงหน้าฟอร์มสร้างแบบทดสอบและรายการแบบทดสอบทั้งหมด
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $createdBy = Auth::id();
        $quizzes = [];
        $id = auth()->user()->student_id;
        $lavel = str_split($id, 1)[3];
        $subject = "subject".$lavel;

        if ($createdBy) {
            // ดึงข้อมูลแบบทดสอบทั้งหมดที่ผู้ใช้คนปัจจุบันสร้างขึ้น พร้อมดึงข้อมูลวิชาที่เกี่ยวข้อง
            $quizzes = DB::table('quizzes')
                         ->select('quizzes.*') // แก้ไขเป็น SUB_CODE และ SUB_NAME ตาม schema
                         ->where('quizzes.created_by', $createdBy)
                         ->orderByDesc('quizzes.created_at')
                         ->get();
        }

        return view('teachers.ttest', compact('quizzes'));
    }

    /**
     * จัดการการบันทึกข้อมูลแบบทดสอบลงในฐานข้อมูล
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูลที่ส่งมา (Validation)
        $request->validate([
            'quiz_title' => 'required|string|max:255',
            'quiz_description' => 'nullable|string',
            'subject_code' => 'required|string|max:255',
            'time_limit' => 'nullable|integer|min:0',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'questions.*.score' => 'required|integer|min:1',
            'questions.*.choices' => 'array',
            'questions.*.choices.*.choice_text' => 'required_if:questions.*.question_type,multiple_choice|string',
            'questions.*.choices.*.is_correct' => 'nullable|boolean',
        ], [
            'quiz_title.required' => 'กรุณากรอกชื่อแบบทดสอบ',
            'subject_code.required' => 'กรุณากรอกรหัสวิชา',
            'questions.required' => 'กรุณาเพิ่มคำถามอย่างน้อย 1 ข้อ',
            'questions.*.question_text.required' => 'กรุณากรอกข้อความคำถาม',
            'questions.*.score.required' => 'กรุณากรอกคะแนนสำหรับคำถาม',
            'questions.*.choices.*.choice_text.required_if' => 'กรุณากรอกข้อความตัวเลือกสำหรับคำถามปรนัย',
        ]);

        $createdBy = Auth::id();
        if (!$createdBy) {
            return redirect()->back()->with('error', 'คุณต้องเข้าสู่ระบบก่อนจึงจะสร้างแบบทดสอบได้');
        }

        DB::beginTransaction();

        try {
            $totalQuizScore = 0;

            // 2. บันทึกข้อมูลแบบทดสอบ (quizzes table)
            $quizId = DB::table('quizzes')->insertGetId([
                'title' => $request->input('quiz_title'),
                'description' => $request->input('quiz_description'),
                'total_score' => 0, // ตั้งต้นเป็น 0 จะคำนวณและอัปเดตในภายหลัง
                'time_limit' => $request->input('time_limit', 0),
                'created_by' => $createdBy,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. วนลูปบันทึกข้อมูลคำถาม (questions table) และตัวเลือก (choices table)
            foreach ($request->input('questions') as $questionData) {
                $questionId = DB::table('questions')->insertGetId([
                    'quiz_id' => $quizId,
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'score' => $questionData['score'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // เพิ่มคะแนนของคำถามแต่ละข้อเข้าสู่คะแนนรวม
                $totalQuizScore += $questionData['score'];

                // ถ้าเป็นคำถามปรนัย ให้บันทึกตัวเลือก
                if ($questionData['question_type'] === 'multiple_choice' && isset($questionData['choices'])) {
                    foreach ($questionData['choices'] as $choiceData) {
                        DB::table('choices')->insert([
                            'question_id' => $questionId,
                            'choice_text' => $choiceData['choice_text'],
                            'is_correct' => isset($choiceData['is_correct']) && $choiceData['is_correct'] ? 1 : 0,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            // 4. อัปเดต total_score ในตาราง quizzes
            DB::table('quizzes')
                ->where('id', $quizId)
                ->update(['total_score' => $totalQuizScore]);

            // หากทุกอย่างสำเร็จ ให้ Commit Transaction
            DB::commit();

            return redirect()->back()->with('success', 'สร้างแบบทดสอบสำเร็จแล้ว!');

        } catch (\Exception $e) {
            // หากเกิดข้อผิดพลาด ให้ Rollback Transaction
            DB::rollBack();
            Log::error('Error creating quiz: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการสร้างแบบทดสอบ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * แสดงหน้าฟอร์มสำหรับแก้ไขแบบทดสอบที่ระบุ
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $createdBy = Auth::id();
        $quiz = DB::table('quizzes')
                     ->where('id', $id)
                     ->where('created_by', $createdBy) // ตรวจสอบว่าผู้ใช้เป็นเจ้าของแบบทดสอบ
                     ->first();

        if (!$quiz) {
            return redirect()->back()->with('error', 'ไม่พบแบบทดสอบที่คุณต้องการแก้ไข หรือคุณไม่มีสิทธิ์เข้าถึง');
        }

        // ดึงข้อมูลวิชาที่เกี่ยวข้องเพื่อแสดงรหัสวิชาในฟอร์มแก้ไข
        $subject = DB::table('subjects')
                      ->where('id', $quiz->subject_id) // ใช้ subject_id จากตาราง quizzes
                      ->first();

        // เพิ่ม subject_code เข้าไปในวัตถุ quiz เพื่อส่งไปยัง view
        $quiz->subject_code = $subject ? $subject->SUB_CODE : ''; // แก้ไขเป็น SUB_CODE ตาม schema

        // ดึงคำถามและตัวเลือกของแบบทดสอบ
        $questions = DB::table('questions')
                        ->where('quiz_id', $quiz->id)
                        ->get();

        foreach ($questions as $question) {
            if ($question->question_type === 'multiple_choice') {
                $question->choices = DB::table('choices')
                                         ->where('question_id', $question->id)
                                         ->get();
            }
        }

        return view('teachers.edit-ttest', compact('quiz', 'questions'));
    }

    /**
     * อัปเดตแบบทดสอบที่ระบุในฐานข้อมูล
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validation (คล้ายกับ store method แต่ปรับสำหรับการอัปเดต)
        $request->validate([
            'quiz_title' => 'required|string|max:255',
            'quiz_description' => 'nullable|string',
            'subject_code' => 'required|string|max:255',
            'time_limit' => 'nullable|integer|min:0',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'questions.*.score' => 'required|integer|min:1',
            'questions.*.choices' => 'array',
            'questions.*.choices.*.choice_text' => 'required_if:questions.*.question_type,multiple_choice|string',
            'questions.*.choices.*.is_correct' => 'nullable|boolean',
        ], [
            'quiz_title.required' => 'กรุณากรอกชื่อแบบทดสอบ',
            'subject_code.required' => 'กรุณากรอกรหัสวิชา',
            'questions.required' => 'กรุณาเพิ่มคำถามอย่างน้อย 1 ข้อ',
            'questions.*.question_text.required' => 'กรุณากรอกข้อความคำถาม',
            'questions.*.score.required' => 'กรุณากรอกคะแนนสำหรับคำถาม',
            'questions.*.choices.*.choice_text.required_if' => 'กรุณากรอกข้อความตัวเลือกสำหรับคำถามปรนัย',
        ]);

        $createdBy = Auth::id();
        $quiz = DB::table('quizzes')
                     ->where('id', $id)
                     ->where('created_by', $createdBy)
                     ->first();

        if (!$quiz) {
            return redirect()->back()->with('error', 'ไม่พบแบบทดสอบที่คุณต้องการแก้ไข หรือคุณไม่มีสิทธิ์เข้าถึง');
        }

        $subject = DB::table('subjects')
                     ->where('SUB_CODE', $request->input('subject_code')) // ค้นหาด้วย SUB_CODE
                     ->first();

        if (!$subject) {
            return redirect()->back()->with('error', 'ไม่พบวิชาที่มีรหัสนี้ กรุณาตรวจสอบรหัสวิชา')->withInput();
        }

        DB::beginTransaction();

        try {
            $totalQuizScore = 0;

            // อัปเดตข้อมูลแบบทดสอบ
            DB::table('quizzes')
                ->where('id', $quiz->id)
                ->update([
                    'title' => $request->input('quiz_title'),
                    'description' => $request->input('quiz_description'),
                    'subject_id' => $subject->id, // แก้ไข: บันทึก ID ของวิชา ไม่ใช่รหัสวิชา
                    'time_limit' => $request->input('time_limit', 0),
                    'updated_at' => now(),
                ]);

            // ลบคำถามและตัวเลือกที่มีอยู่ทั้งหมดของแบบทดสอบนี้ก่อน
            // วิธีนี้ง่ายต่อการจัดการ แต่ถ้ามีข้อมูลเยอะมากอาจจะไม่เหมาะสม
            // ควรใช้วิธีเปรียบเทียบและอัปเดต/ลบ/เพิ่มเป็นรายข้อ เพื่อประสิทธิภาพที่ดีกว่า
            DB::table('choices')->whereIn('question_id', function($query) use ($quiz) {
                $query->select('id')->from('questions')->where('quiz_id', $quiz->id);
            })->delete();
            DB::table('questions')->where('quiz_id', $quiz->id)->delete();

            // แทรกคำถามและตัวเลือกใหม่/ที่อัปเดต
            foreach ($request->input('questions') as $questionData) {
                $questionId = DB::table('questions')->insertGetId([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'score' => $questionData['score'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalQuizScore += $questionData['score'];

                if ($questionData['question_type'] === 'multiple_choice' && isset($questionData['choices'])) {
                    foreach ($questionData['choices'] as $choiceData) {
                        DB::table('choices')->insert([
                            'question_id' => $questionId,
                            'choice_text' => $choiceData['choice_text'],
                            'is_correct' => isset($choiceData['is_correct']) && $choiceData['is_correct'] ? 1 : 0,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            // อัปเดต total_score สำหรับแบบทดสอบ
            DB::table('quizzes')
                ->where('id', $quiz->id)
                ->update(['total_score' => $totalQuizScore, 'updated_at' => now()]);

            DB::commit();

            return redirect()->route('ttest.index')->with('success', 'แบบทดสอบได้รับการอัปเดตเรียบร้อยแล้ว!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating quiz: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตแบบทดสอบ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ลบแบบทดสอบที่ระบุออกจากฐานข้อมูล
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $createdBy = Auth::id();
        $quiz = DB::table('quizzes')
                     ->where('id', $id)
                     ->where('created_by', $createdBy)
                     ->first();

        if (!$quiz) {
            return redirect()->back()->with('error', 'ไม่พบแบบทดสอบที่คุณต้องการลบ หรือคุณไม่มีสิทธิ์เข้าถึง');
        }

        DB::beginTransaction();
        try {
            // การลบแบบทดสอบจะทำให้คำถามและตัวเลือกที่เกี่ยวข้องถูกลบไปด้วย หากตั้งค่า Foreign Key เป็น ON DELETE CASCADE
            DB::table('quizzes')->where('id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'แบบทดสอบถูกลบเรียบร้อยแล้ว!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting quiz: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบแบบทดสอบ: ' . $e->getMessage());
        }
    }
}