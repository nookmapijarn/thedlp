<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    protected $quizId;

    // รับค่า ID ของชุดข้อสอบมาจาก Controller
    public function __construct($quizId)
    {
        $this->quizId = $quizId;
    }

    public function model(array $row)
    {
        // 1. บันทึกโจทย์ลงตาราง questions (อิงตามฟิลด์จาก questions.dbf)
        $questionId = DB::table('questions')->insertGetId([
            'quiz_id'        => $this->quizId,
            'question_text'  => $row['question_text'], // โจทย์
            'question_type'  => $row['question_type'] ?? 'multiple_choice',
            'score'          => $row['score'] ?? 1,
            'indicator'      => $row['indicator'] ?? null,   // ตัวชี้วัด
            'taxonomy_level' => $row['taxonomy_level'] ?? null, // ระดับพฤติกรรม
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 2. เตรียมข้อมูลตัวเลือก (อิงตามฟิลด์จาก choices.dbf)
        // สมมติ Excel มีคอลัมน์ choice_1, choice_2, choice_3, choice_4 และ answer (เป็นเลข 1-4)
        $choices = [
            1 => $row['choice_1'] ?? null,
            2 => $row['choice_2'] ?? null,
            3 => $row['choice_3'] ?? null,
            4 => $row['choice_4'] ?? null,
        ];

        foreach ($choices as $index => $text) {
            if (!empty($text)) {
                DB::table('choices')->insert([
                    'question_id' => $questionId,
                    'choice_text' => $text,
                    'is_correct'  => ($row['answer'] == $index) ? 1 : 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        return null; // คืนค่า null เพราะเราใช้ DB facade บันทึกเองหลายตาราง
    }
}