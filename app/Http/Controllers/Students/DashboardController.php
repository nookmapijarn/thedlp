<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RoleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $lavel; // คงไว้ตามเดิม
    protected $std_code;

    public function index()
    {
        // --- SET ข้อมูลพื้นฐาน ---
        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $lavel = $this->lavel;
        
        // ดึง STD_CODE
        $this->std_code = DB::table("student{$this->lavel}")
            ->where('ID', $id)
            ->value('STD_CODE');

        // ดึงข้อมูลหลัก
        $student = $this->get_student();
        $activity = $this->get_activity();
        $gradelist = $this->get_gradelist();
        $grade_analyze = $this->get_grade_analyze();
        
        // ตรวจสอบข้อมูลนักเรียน
        if (count($student) == 0) {
            $role = auth()->user()->role;
            return redirect()->to("welcome?roletype={$role}&studentnull=true");
        }

        // --- คำนวณกิจกรรม ---
        $act_sum = 0;
        foreach ($activity as $p) {
            $act_sum += $p->HOUR;
        }
        $act_percentage = round(($act_sum * 100) / 200, 0);

        // --- รายการภาคเรียน ---
        $semestrylist1 = DB::table("grade{$this->lavel}")
            ->where('STD_CODE', $this->std_code)
            ->select('SEMESTRY')
            ->distinct()
            ->orderBy('SEMESTRY', 'ASC')
            ->get();

        // --- คำนวนหน่วยกิต ---
        $credit_data = $this->cal_credit($gradelist);
        $credit = $credit_data['CREDIT'];
        $allcredit = $credit_data['ALL_CREDIT'];
        
        if ($allcredit != 0 && $allcredit != null) {
            $credit_percent = round(($credit * 100) / $allcredit, 0);
        } else {
            $credit_percent = 0;
        }

        // --- เกรดเฉลี่ย ระยะเวลาเรียน และ การเข้าสอบ ---
        $grade_avg = $this->grade_avg($gradelist);
        $exam_avg = $this->exam_avg($gradelist);
        $timelerning = $this->timelerning();
        
        $nnet = null;
        $moral = null;
        if (count($student) > 0) {
            $nnet = $student[0]->ABLEVEL2 == 1 ? 'ผ่าน' : '-';  
            $moral = $student[0]->ABLEVEL1;         
        }

        // --- เตรียม Array เกรด (ดึงข้อมูลวิชามาแสดง) ---
        $grade = [];
        foreach ($gradelist as $g) {
            $subjectData = $this->getSubject($g->SUB_CODE);
            array_push($grade, [
                'sub_code' => $g->SUB_CODE, 
                'sub_name' => $subjectData->SUB_NAME,
                'grade'    => $g->GRADE,
                'semestry' => $g->SEMESTRY,
            ]);
        }

        // ส่งค่าไปยัง View (คงชื่อตัวแปรเดิมทั้งหมด)
        return view('students.dashboard', compact(
            'grade',
            'lavel',
            'semestrylist1', 
            'student',
            'act_sum',
            'act_percentage',
            'credit',
            'allcredit',
            'credit_percent',
            'grade_avg',
            'exam_avg',
            'moral',
            'nnet',
            'grade_analyze',
            'timelerning'
        ));
    }

    // --- Methods เสริม (คงชื่อเดิมและ Logic เดิม) ---

    public function get_student() {
        return DB::table("student{$this->lavel}")
            ->where('STD_CODE', $this->std_code)
            ->get();
    }

    public function get_activity() {
        return DB::table("activity{$this->lavel}")
            ->where('STD_CODE', $this->std_code)
            ->get();
    }

    public function lavelis() {
        $str = auth()->user()->student_id;
        $split = str_split($str, 1);
        return $split[3];
    }

    public function timelerning() {
        $semtime = DB::table("grade{$this->lavel}")
            ->where('STD_CODE', $this->std_code)
            ->select('SEMESTRY')
            ->distinct()
            ->get();
        return $semtime->count() * 10;
    }

    public function grade_avg($gradelist) {
        $grade = 0;
        $all_grade = 0;
        $grade_avg = 0;
        foreach ($gradelist as $g) {
            if (is_numeric($g->GRADE) && $g->GRADE != 0) {   
                $grade += $g->GRADE;
                $all_grade++;
            }
        }
        if ($all_grade != 0) {
            $grade_avg = round($grade / $all_grade, 2);
        }       
        return $grade_avg;
    }

    public function exam_avg($gradelist) {
        $exam = 0;
        $exam_all = count($gradelist);
        $exam_avg = 0;
        foreach ($gradelist as $g) {
            if (is_numeric($g->GRADE)) {   
                $exam++;
            }
        }
        if ($exam_all != 0) {
            $exam_avg = round(($exam / $exam_all) * 100, 2);
        }
        return round($exam_avg, 0);
    }

    public function get_gradelist() {
        return DB::table("grade{$this->lavel}")
            ->where('STD_CODE', $this->std_code)
            ->get();
    }

    public function get_grade_analyze() {
        $learning = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^ทร')->get();
        $besic    = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^พ')->get();
        $career   = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^อ')->get();
        $life     = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^ทช')->get();
        $society  = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^ส')->get();
        
        $learning = ($learning->count() != 0) ? $learning->sum('TOTAL') / $learning->count() : 0;
        $besic    = ($besic->count() != 0) ? $besic->sum('TOTAL') / $besic->count() : 0;
        $career   = ($career->count() != 0) ? $career->sum('TOTAL') / $career->count() : 0;
        $life     = ($life->count() != 0) ? $life->sum('TOTAL') / $life->count() : 0;
        $society  = ($society->count() != 0) ? $society->sum('TOTAL') / $society->count() : 0;
        
        return [round($learning, 2), round($besic, 2), round($career, 2), round($life, 2), round($society, 2)];
    }

    public function cal_credit($gradelist) {
        $credit = 0;
        $allcredit = 0;

        foreach ($gradelist as $g) {
            if (is_numeric($g->GRADE) && $g->GRADE != 0) {   
                $credit += $this->getSubject($g->SUB_CODE)->SUB_CREDIT;
            }
        }

        switch ($this->lavelis()) {
            case "3": $allcredit = 76; break;
            case "2": $allcredit = 57; break;
            case "1": $allcredit = 48; break;
        }

        return ['CREDIT' => $credit, 'ALL_CREDIT' => $allcredit];
    }

    public function getSubject($sub_code) {
        $subject = DB::table("subject{$this->lavel}")
            ->where('SUB_CODE', $sub_code)
            ->first(); // ใช้ first แทน get เพื่อความเร็ว

        if ($subject) {
            return $subject;
        } else {
            return (object) [
                'SUB_NAME' => '**** ไม่มีข้อมูลในตารางรายวิชา ****',
                'SUB_CREDIT' => 0
            ];
        }
    }

    public function getStudentidByUser() {
        return auth()->user()->student_id;
    }
}