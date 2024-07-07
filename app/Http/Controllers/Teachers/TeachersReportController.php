<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Providers\CustomServiceProvider;


class TeachersReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct(CustomServiceProvider $customService)
    // {
    //     $customService->setSemestry(66/2);
    // }
    protected $std_code;

    public function index(Request $request)
    {
        ini_set('max_execution_time', '512M');

        $all_semestry = $this->get_semestry();
        $lavel = $request->lavel;
        $data=null;
        $tumbon = '0000';
        $studreport = '';
        $semestry = $all_semestry->first()->SEMESTRY;
        $all_tumbon = $this->get_group($semestry);

        if($request->tumbon!=''){
            $grp_code = str_split($request->tumbon, 4)[0];
            $semestry = $request->semestry;
            $tumbon = $request->tumbon;
            $studreport = $request->studreport;
        }else{
            return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry', 'lavel'));
        }

        // เลือกรายงาน
        switch ($studreport) {
            case 'นักศึกษาทั้งหมด':
                $data = $this->allstudent($tumbon, $semestry, $lavel);
                $data = collect($data)->sortBy('lavel')->toArray(); // $mystudent = collect($mystudent)->sortBy('lavel')->reverse()->toArray(); DESC
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry', 'lavel'));
              break;
            case 'เฉพาะผู้คาดว่าจะจบ':
                $data = $this->expstudent($tumbon, $semestry, $lavel);
                $data = collect($data)->sortBy('lavel')->toArray();
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry', 'lavel'));
              break;
            case 'ไม่จบตกค้าง(ที่ไม่ได้ลงทะเบียนแล้ว)':
                $data = $this->unfinishstudent($tumbon, $semestry, $lavel);
                $data = collect($data)->sortBy('lavel')->toArray();
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry', 'lavel'));
              break;
            default:
              return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry', 'lavel'));
          }                       
    }

    public function allstudent($grp_code, $semestry, $lavel){
        
        $allstudent = [];
        $current_student = $this->current_student($grp_code, $semestry, $lavel);

        foreach ($current_student as $s) {
            $level = $this->lavelis($s->STD_CODE);
            switch ($this->expfin($s->STD_CODE, $level)) {
                case true :
                $expfin = 1;
                $nnet = ($s->NT_SEM!='' ? 'ผ่านแล้ว' : ($s->NT_NOSEM!='' ? 'E-Exam': 'มีสิทธิ'));
                break;
                case false:
                $expfin = 0;
                $nnet = ($s->NT_SEM!='' ? $s->NT_SEM : ($s->NT_NOSEM!='' ? 'E-Exam': '-'));
                break;
                default:
                    $expfin = '*';
                    $nnet = '*';
            }
            array_push($allstudent, 
            [
                'id'        =>  $s->ID,
                'lavel'     =>  $level,  
                'name'      =>  $s->NAME,
                'surname'   =>  $s->SURNAME,
                'fin_cause' =>  $s->FIN_CAUSE,
                'expfin'    =>  $expfin,
                'activity'  =>  $this->get_activity($s->STD_CODE, $level),
                'nt_sem'    =>  $nnet,
                'grp_code'  =>  $s->GRP_CODE,
                'ablevel1'  =>  $s->ABLEVEL1
            ]);
        }

        $allstudent = count($allstudent) !== 0 ? $allstudent :  null;
        return $allstudent;
    }

    public function expstudent($grp_code, $semestry, $lavel)
    {
        $expstudents = [];
        for ($i = 1; $i <= 3; $i++) {
            $current_students = $this->current_student($grp_code, $semestry, $lavel);
            if ($current_students->count() != 0) {
                foreach ($current_students as $s) {
                    if($this->expfin($s->STD_CODE, $i)){
                        $expstudents[] = [
                            'id' => $s->ID,
                            'lavel' => $i,
                            'name' => $s->NAME,
                            'surname' => $s->SURNAME,
                            'fin_cause' => $s->FIN_CAUSE,
                            'expfin' => true,
                            'activity' => $this->get_activity($s->STD_CODE, $i),
                            'nt_sem' => ($s->NT_SEM != '') ? 'ผ่านแล้ว' : (($s->NT_NOSEM != '') ? 'E-Exam' : 'มีสิทธิ'),
                            'grp_code' => $s->GRP_CODE,
                            'ablevel1' => $s->ABLEVEL1
                        ];
                    } else {
                        continue;
                    }

                }
            }
        }
        return count($expstudents) !== 0 ? $expstudents : null;
    }
    

    public function unfinishstudent($grp_code, $semestry, $lavel)
    {
        $unfinishstudent = [];
        
        $not_current_students = $this->not_current_student($grp_code, $semestry, $lavel);

        foreach ($not_current_students as $s) {
            
            $lavel = str_split($s->ID, 1)[3];

            if ($this->expfin($s->STD_CODE, $lavel)) {
                $tgrade = "grade{$lavel}";
                $std_code = $s->STD_CODE;

                $current_count = DB::table($tgrade)->where('STD_CODE', $std_code)->where("SEMESTRY", $semestry)->count();

                if ($current_count > 0) {
                    // echo '<br><br><br><br>*********************************************************** ลงทะเบียนในภาคเรียนปัจจุบัน ***************************************!!! <br>';
                    // echo '*********************************************************** ' . $semestry . ' ' . $s->ID . ' ' . $s->NAME . ' ' . $s->SURNAME . ' ***************************************!!! <br>';
                } else {
                    // echo '<br><br><br><br>*********************************************************** ไม่ได้ลงทะเบียนในภาคเรียนปัจจุบัน ***************************************!!! <br>';
                    // echo '*********************************************************** ' . $semestry . ' ' . $s->ID . ' ' . $s->NAME . ' ' . $s->SURNAME . ' ***************************************!!! <br>';
                    
                    $unfinishstudent[] = [
                        'id' => $s->ID,
                        'lavel' => $lavel,
                        'name' => $s->NAME,
                        'surname' => $s->SURNAME,
                        'fin_cause' => $s->FIN_CAUSE,
                        'expfin' => true,
                        'activity' => $this->get_activity($s->STD_CODE, $lavel),
                        'nt_sem' => ($s->NT_SEM != '') ? 'ผ่านแล้ว' : (($s->NT_NOSEM != '') ? 'E-Exam' : 'มีสิทธิ'),
                        'grp_code' => $s->GRP_CODE,
                        'ablevel1' => $s->ABLEVEL1
                    ];
                }
            }
        }
    
        return !empty($unfinishstudent) ? $unfinishstudent : null;
    }
    
    // ข้อมูลนักศึกษา
    public function get_student($std_code, $lavel){

        $tstudent = "student{$lavel}";

        $student = DB::table($tstudent)
        ->where('STD_CODE', $std_code)
        ->select(array('ID', 'STD_CODE', 'NAME', 'SURNAME', 'FIN_CAUSE', 'NT_SEM', 'NT_NOSEM', 'GRP_CODE'))
        ->get();
        return $student;
    }

    // คาดว่าจะจบ
    public function expfin($std_code, $lavel){
        
        $tgrade = "grade{$lavel}";
        $subject = "subject{$lavel}";
        $current_semestry = $this->get_semestry()->first()->SEMESTRY;

        try {

            $grade = DB::table($tgrade)
            ->join($subject, "$tgrade.SUB_CODE", '=', "$subject.SUB_CODE")
            ->where("$tgrade.STD_CODE", '=', $std_code)
            ->where(function($query) use ($tgrade, $current_semestry) {
                $query->where("$tgrade.GRADE", 'REGEXP', '[1-4]')
                      ->orWhere(function($subQuery) use ($tgrade, $current_semestry) {
                          $subQuery->whereNull("$tgrade.GRADE")
                                   ->where("$tgrade.SEMESTRY", $current_semestry);
                      });
            })
            ->select("$tgrade.STD_CODE", "$tgrade.SUB_CODE", "$tgrade.SEMESTRY", "$tgrade.TYP_CODE", "$tgrade.GRADE", "$subject.SUB_CREDIT")
            ->groupBy("$tgrade.STD_CODE", "$tgrade.SUB_CODE", "$tgrade.SEMESTRY", "$tgrade.TYP_CODE", "$tgrade.GRADE", "$subject.SUB_CREDIT")
            ->get();
    
        } catch (\Exception $e) {
            echo (' NOT Query' . $e->getMessage()).'<br>';
        }
        // echo '<pre>';
        // echo '******************************************'.print_r($grade);
        // echo '</pre>';

        $sum_credit = $grade->sum('SUB_CREDIT');

        if (($lavel == 1 && $sum_credit >= 48) || ($lavel == 2 && $sum_credit >= 55) || ($lavel == 3 && $sum_credit >= 76)) {
            return true;
        } else {
            return false;
        }
    }

    
    public function current_student($grp_code, $semestry, $lavel)
    {
        // กำหนดค่าเริ่มต้นให้กับ $grp_code
        $grp_code = ($grp_code == '0000' || $grp_code == ' ' || $grp_code == null) ? "[0-9]" : $grp_code;
    
        if($lavel != null){

            // เลือกระดับชั้น 
            // ถ้ารหัสกลุ่มเป็น "[0-9]" จะใช้ REGEXP สำหรับการจับคู่รูปแบบ (Regular Expression)
            // ถ้าไม่ใช่จะใช้การจับคู่แบบตรงๆ ด้วย =
            $tgrade = "grade{$lavel}";
            $tstudent = "student{$lavel}";

            $current_students = DB::table($tgrade)
                ->where("$tgrade.SEMESTRY", $semestry)
                ->where(function($query) use ($grp_code, $tgrade) {
                    if ($grp_code === "[0-9]") {
                        $query->whereRaw("$tgrade.GRP_CODE REGEXP ?", [$grp_code]);
                    } else {
                        $query->where("$tgrade.GRP_CODE", '=', $grp_code);
                    }
                })
                ->join($tstudent, "$tgrade.STD_CODE", '=', "$tstudent.STD_CODE")
                ->select("$tstudent.STD_CODE", "$tstudent.ID", "$tstudent.NAME", "$tstudent.SURNAME", "$tstudent.FIN_CAUSE", "$tstudent.NT_SEM", "$tstudent.NT_NOSEM", "$tstudent.GRP_CODE",  "$tstudent.ABLEVEL1", "$tstudent.ABLEVEL2")
                ->distinct()
                ->get();

                return $current_students;

        } else {
            // ส่งค่า $lavel = null จะเอาทุกระดับ
            // รวมข้อมูลจากตาราง grade1, grade2 และ grade3
            $gradeTables = ['grade1', 'grade2', 'grade3'];
            $studentTables = ['student1', 'student2', 'student3'];
            $students = collect();
        
            foreach ($gradeTables as $index => $tgrade) {
                $tstudent = $studentTables[$index];
        
                $current_students = DB::table($tgrade)
                    ->where("$tgrade.SEMESTRY", $semestry)
                    ->where(function($query) use ($grp_code, $tgrade) {
                        if ($grp_code === "[0-9]") {
                            $query->whereRaw("$tgrade.GRP_CODE REGEXP ?", [$grp_code]);
                        } else {
                            $query->where("$tgrade.GRP_CODE", '=', $grp_code);
                        }
                    })
                    ->join($tstudent, "$tgrade.STD_CODE", '=', "$tstudent.STD_CODE")
                    ->select("$tstudent.STD_CODE", "$tstudent.ID", "$tstudent.NAME", "$tstudent.SURNAME", "$tstudent.FIN_CAUSE", "$tstudent.NT_SEM", "$tstudent.NT_NOSEM", "$tstudent.GRP_CODE",  "$tstudent.ABLEVEL1", "$tstudent.ABLEVEL2")
                    ->distinct()
                    ->get();
        
                $students = $students->merge($current_students); // รวมข้อมูลจากทุกระดับชั้น
            }
        
            return $students;
        }
    }
    

    public function not_current_student($grp_code, $semestry, $lavel)
    {
        $grp_code = ($grp_code == '0000' || $grp_code == ' ' || $grp_code == null) ? "[0-9]" : $grp_code;

        // แยกส่วนปีและภาคเรียน
        list($current_year, $current_term) = explode('/', $semestry);
    
        // แปลงเป็นตัวเลข
        $current_year = (int)$current_year;
        $current_term = (int)$current_term;
    
        // คำนวณภาคเรียนที่ย้อนหลัง 10 ภาคเรียน
        $target_year = $current_year - 3;
        $target_term = $current_term;
    
        // สร้างช่วงภาคเรียน
        $valid_semesters = [];
        for ($y = $current_year; $y >= $target_year; $y--) {
            for ($t = ($y == $current_year) ? $current_term : 2; $t >= 1; $t--) {
                $valid_semesters[] = "{$y}/{$t}";
                if ($y == $target_year && $t == $target_term) break; // หยุดถ้าถึงภาคเรียนเป้าหมาย
            }
        }
                
        $grp_code = ($grp_code == '0000' || $grp_code == ' ' || $grp_code == null) ? "[0-9]" : $grp_code;
        $students = collect(); // ใช้ collection เพื่อสะสมข้อมูลจากทุกระดับชั้น
    
        if($lavel != null){

            // เลือกระดับชั้น
            $tgrade = "grade{$lavel}";
            $tstudent = "student{$lavel}";

            $not_current_student = DB::table($tgrade)
                ->join($tstudent, "$tgrade.STD_CODE", '=', "$tstudent.STD_CODE")
                ->whereNotIn("$tgrade.SEMESTRY", [$semestry]) // นักศึกษาที่ยังไม่ได้ลงทะเบียนในภาคเรียนปัจจุบัน
                ->whereIn("$tgrade.SEMESTRY", $valid_semesters) // เลือกเฉพาะภาคเรียนที่ต้องการ (ไม่เกิน 10 ภาคเรียนย้อนหลัง)
                ->where(function($query) use ($grp_code, $tgrade) {
                    if ($grp_code === "[0-9]") {
                        $query->whereRaw("$tgrade.GRP_CODE REGEXP ?", [$grp_code]);
                    } else {
                        $query->where("$tgrade.GRP_CODE", '=', $grp_code);
                    }
                })
                //->whereNull("$tgrade.GRADE") // ตรวจสอบว่ารายการนี้ยังไม่ได้รับเกรด
                ->where("$tstudent.FIN_SEM", '=', null) // fin_sem != 1-9
                ->select("$tstudent.STD_CODE", "$tstudent.ID", "$tstudent.NAME", "$tstudent.SURNAME", "$tstudent.FIN_CAUSE", "$tstudent.NT_SEM", "$tstudent.NT_NOSEM", "$tstudent.GRP_CODE",  "$tstudent.ABLEVEL1", "$tstudent.ABLEVEL2")
                ->groupBy("$tstudent.STD_CODE", "$tstudent.ID", "$tstudent.NAME", "$tstudent.SURNAME", "$tstudent.FIN_CAUSE", "$tstudent.NT_SEM", "$tstudent.NT_NOSEM", "$tstudent.GRP_CODE",  "$tstudent.ABLEVEL1", "$tstudent.ABLEVEL2")
                ->get();

            return $not_current_student;

        } else {
            
            // ไม่เลือกระดับ

            for ($i = 1; $i <= 3; $i++) {
                $tgrade = "grade{$i}";
                $tstudent = "student{$i}";
        
                $not_current_student = DB::table($tgrade)
                    ->join($tstudent, "$tgrade.STD_CODE", '=', "$tstudent.STD_CODE")
                    ->whereNotIn("$tgrade.SEMESTRY", [$semestry]) // นักศึกษาที่ยังไม่ได้ลงทะเบียนในภาคเรียนปัจจุบัน
                    ->whereIn("$tgrade.SEMESTRY", $valid_semesters) // เลือกเฉพาะภาคเรียนที่ต้องการ (ไม่เกิน 10 ภาคเรียนย้อนหลัง)
                    ->where(function($query) use ($grp_code, $tgrade) {
                        if ($grp_code === "[0-9]") {
                            $query->whereRaw("$tgrade.GRP_CODE REGEXP ?", [$grp_code]);
                        } else {
                            $query->where("$tgrade.GRP_CODE", '=', $grp_code);
                        }
                    })
                    //->whereNull("$tgrade.GRADE") // ตรวจสอบว่ารายการนี้ยังไม่ได้รับเกรด
                    ->where("$tstudent.FIN_SEM", '=', null) // fin_sem != 1-9
                    ->select("$tstudent.STD_CODE", "$tstudent.ID", "$tstudent.NAME", "$tstudent.SURNAME", "$tstudent.FIN_CAUSE", "$tstudent.NT_SEM", "$tstudent.NT_NOSEM", "$tstudent.GRP_CODE",  "$tstudent.ABLEVEL1", "$tstudent.ABLEVEL2")
                    ->groupBy("$tstudent.STD_CODE", "$tstudent.ID", "$tstudent.NAME", "$tstudent.SURNAME", "$tstudent.FIN_CAUSE", "$tstudent.NT_SEM", "$tstudent.NT_NOSEM", "$tstudent.GRP_CODE",  "$tstudent.ABLEVEL1", "$tstudent.ABLEVEL2")
                    ->get();
                
                $students = $students->merge($not_current_student); // รวมข้อมูลจากทุกระดับชั้น
            }
            return $students;
        }
    }

    public function get_activity($std_code, $lavel){
        $tact = "activity{$lavel}";
        $activity = DB::table($tact)
        ->where('STD_CODE', $std_code)
        ->sum('HOUR');
        return $activity;
    }
    public function lavelis($student_id){
        // ดูระดับชั้น
        $str = $student_id;
        $split = str_split($str, 1);
        return $split[13];
    }
    public function get_semestry()
    {
        $semestry1 = DB::table('grade1')
            ->select('SEMESTRY')
            ->orderBy('SEMESTRY', 'DESC')
            ->groupBy('SEMESTRY');
        $semestry2 = DB::table('grade2')
            ->union($semestry1)
            ->select('SEMESTRY')
            ->orderBy('SEMESTRY', 'DESC')
            ->groupBy('SEMESTRY');
        $semestry3 = DB::table('grade3')
            ->union($semestry2)
            ->select('SEMESTRY')
            ->orderBy('SEMESTRY', 'DESC')
            ->groupBy('SEMESTRY')
            ->get();
        // echo '<pre>';
        // echo print_r($semestry3);
        // echo '</pre>';
        return $semestry3;
    }
    public function get_group($semestry)
    {
        // Implement the function to get all groups
        $Group = collect(); // ใช้ collection เพื่อเก็บผลลัพธ์
    
        for ($i = 1; $i <= 3; $i++) {
            $results = DB::table("grade{$i}")
                ->where("grade{$i}.SEMESTRY", $semestry)
                ->join("group", "group.GRP_CODE", '=', "grade{$i}.GRP_CODE")
                ->select("group.GRP_CODE", "group.GRP_NAME", "group.GRP_ADVIS")
                ->groupBy("group.GRP_CODE", "group.GRP_NAME", "group.GRP_ADVIS")
                ->orderBy("GRP_CODE", "ASC")
                ->get();
    
            $Group = $Group->merge($results)->unique()->sort(); // รวมผลลัพธ์
        }
        return $Group;
    }
}
