<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\AiConversation;
use Illuminate\Support\Facades\Log;

class OlisAiController extends Controller
{
    protected $level;
    protected $std_code;

    public function index()
    {
        $id = auth()->user()->student_id;
        try {
            $this->level = str_split($id, 1)[3];
            $this->std_code = DB::table("student{$this->level}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');
        } catch (\Exception $e) {
            Log::error("Student data lookup failed in index for ID: {$id}. Error: " . $e->getMessage());
            // Handle this case, perhaps redirect to an error page or show a flash message
            return view('students.olisai', ['conversations' => collect()]);
        }

        $conversations = AiConversation::where('student_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('students.olisai', compact('conversations'));
    }

    public function chat(Request $request)
    {
        $userQuestion = $request->input('question');
        $id = auth()->user()->student_id;

        try {
            $this->level = str_split($id, 1)[3];
            $this->std_code = DB::table("student{$this->level}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');
        } catch (\Exception $e) {
            Log::error("Student data lookup failed for ID: {$id}. Error: " . $e->getMessage());
            return response()->json(['answer' => 'ขออภัย ไม่พบข้อมูลนักศึกษา'], 500);
        }

        // --- ส่วนที่ปรับปรุง: ดึงข้อมูลอย่างปลอดภัยและจัดการโครงสร้างข้อมูล ---
        try {
            $gradeData = DB::table("grade{$this->level}")->where('STD_CODE', $this->std_code)->get()->toArray();
            $activityData = DB::table("activity{$this->level}")->where('STD_CODE', $this->std_code)->get()->toArray();
            $studentData = DB::table("student{$this->level}")->where('STD_CODE', $this->std_code)->get()->toArray();
            $scheduleData = DB::table("schedule{$this->level}")->get()->toArray();

            // ตรวจสอบว่ามีข้อมูลหรือไม่
            $combinedData = [
                'grades' => !empty($gradeData) ? $gradeData : 'ไม่พบข้อมูลเกรด',
                'activities' => !empty($activityData) ? $activityData : 'ไม่พบข้อมูลกิจกรรม',
                'student_info' => !empty($studentData) ? $studentData : 'ไม่พบข้อมูลนักศึกษา',
                'schedules' => !empty($scheduleData) ? $scheduleData : 'ไม่พบข้อมูลตารางเรียน',
            ];
        } catch (\Exception $e) {
            Log::error("Database query failed for student ID {$id}. Error: " . $e->getMessage());
            return response()->json(['answer' => 'ขออภัย เกิดข้อผิดพลาดในการดึงข้อมูลจากฐานข้อมูล'], 500);
        }
        
        $encodedData = json_encode($combinedData, JSON_UNESCAPED_UNICODE);

        // Build the prompt with clear instructions for the AI
        $prompt = "คุณคือที่ปรึกษาด้านการศึกษาสำหรับนักเรียนชาวไทย จงให้คำแนะนำโดยใช้ข้อมูลต่อไปนี้เป็นหลัก และให้คำตอบเป็นภาษาไทยเท่านั้น หากไม่สามารถตอบได้จากข้อมูลที่ให้ ให้ตอบว่า 'ไม่พบข้อมูลที่เกี่ยวข้อง' ไม่ต้องสร้างข้อมูลเพิ่มเติม ห้ามตอบข้อมูลที่ความเสี่ยงต่อ พรบ.คุ้มครองข้อมูลส่วนบุคคลหรือ PDPA.\n\n";
        $prompt .= "ข้อมูลนักศึกษา:\n" . $encodedData . "\n\n";
        $prompt .= "คำถามของนักศึกษา: " . $userQuestion;

        $apiKey = env('GEMINI_API_KEY');

        try {
            $response = Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            // ตรวจสอบว่า API call สำเร็จหรือไม่
            $aiResponse = 'ขออภัย เกิดข้อผิดพลาดในการประมวลผลคำตอบจาก AI';
            if ($response->successful()) {
                $responseBody = $response->json();
                if (isset($responseBody['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiResponse = $responseBody['candidates'][0]['content']['parts'][0]['text'];
                }
            } else {
                Log::error("Gemini API call unsuccessful for student ID {$id}. Status: " . $response->status() . " Body: " . $response->body());
            }
            
        } catch (\Exception $e) {
            Log::error("Gemini API call failed for student ID {$id}. Error: " . $e->getMessage());
            return response()->json(['answer' => 'ขออภัย เกิดข้อผิดพลาดในการเชื่อมต่อกับ AI'], 500);
        }

        // Save the conversation to the database
        AiConversation::create([
            'student_id' => $id,
            'user_question' => $userQuestion,
            'ai_response' => $aiResponse,
        ]);

        return response()->json(['answer' => $aiResponse]);
    }
}