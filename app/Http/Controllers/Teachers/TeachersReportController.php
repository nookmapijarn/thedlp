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

    public function index(Request $request)
    {
        $all_tumbon = DB::table('group')->select('GRP_CODE', 'GRP_NAME')->orderBy('GRP_CODE', 'ASC')->get();
        $all_semestry = DB::table('grade')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $data=null;
        $tumbon = '0000';
        $studreport = '';
        $semestry = $all_semestry->first()->SEMESTRY;

        if($request->tumbon!=''){
            $grp_code = str_split($request->tumbon, 4)[0];
            $semestry = $request->semestry;
            $tumbon = $request->tumbon;
            $studreport = $request->studreport;
        }else{
            return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
        }

        // เลือกรายงาน
        switch ($studreport) {
            case 'นักศึกษาทั้งหมด':
                $data = $this->allstudent($tumbon, $semestry);
                $data = collect($data)->sortBy('lavel')->toArray(); // $mystudent = collect($mystudent)->sortBy('lavel')->reverse()->toArray(); DESC
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
              break;
            case 'เฉพาะผู้คาดว่าจะจบ':
                $data = $this->expstudent($tumbon, $semestry);
                $data = collect($data)->sortBy('lavel')->toArray();
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
              break;
            case 'ไม่จบตกค้าง(ที่ไม่ได้ลงทะเบียนแล้ว)':
                $data = $this->unfinishstudent($tumbon, $semestry);
                $data = collect($data)->sortBy('lavel')->toArray();
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
              break;
            default:
              return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
          }                       
    }

    public function allstudent($grp_code, $semestry){
        $current_student = $this->current_student($grp_code, null, $semestry);
        $allstudent = [];

        foreach ($current_student as $g) {
            $student = $this->get_student($g->STD_CODE);
            foreach ($student as $s){
                $level = $this->lavelis($s->STD_CODE);
                switch ($this->expfin($s->ID, $level)) {
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
                        'activity'  =>  $this->get_activity($s->STD_CODE),
                        'nt_sem'    =>  $nnet,
                        'grp_code'  =>  $s->GRP_CODE
                    ]
                );
            }
        }
        $allstudent = count($allstudent) !== 0 ? $allstudent :  null;
        return $allstudent;
    }

    public function expstudent($grp_code, $semestry)
    {
        $expstudents = [];
        for ($i = 1; $i <= 3; $i++) {
            $current_students = $this->current_student($grp_code, $i, $semestry);
            if ($current_students->count() != 0) {
                foreach ($current_students as $s) {
                    if($this->expfin($s->ID, $i)){
                        $expstudents[] = [
                            'id' => $s->ID,
                            'lavel' => $i,
                            'name' => $s->NAME,
                            'surname' => $s->SURNAME,
                            'fin_cause' => $s->FIN_CAUSE,
                            'expfin' => true,
                            'activity' => $this->get_activity($s->STD_CODE),
                            'nt_sem' => ($s->NT_SEM != '') ? 'ผ่านแล้ว' : (($s->NT_NOSEM != '') ? 'E-Exam' : 'มีสิทธิ'),
                            'grp_code' => $s->GRP_CODE
                        ];
                    } else {
                        continue;
                    }

                }
            }
        }
        return count($expstudents) !== 0 ? $expstudents : null;
    }
    

    public function unfinishstudent($grp_code, $semestry)
    {
        $all_semestry = DB::table('grade')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $current_semestry = $all_semestry->first()->SEMESTRY;
        $expstudents = [];
        for ($i = 1; $i <= 3; $i++) {
            $current_student = $this->not_current_student($grp_code, $i, $semestry);
            if ($current_student->count() != 0) {
                foreach ($current_student as $s) {
                    if($this->expfin($s->ID, $i)){
                        $tgrade = 'grade'.$i;
                        $std_code = '1215040001'.$s->ID;
                        $current_count = DB::table($tgrade)->where('STD_CODE', $std_code)->where("SEMESTRY", $semestry)->count();
                        if($current_count > 0){
                            // echo '<br><br><br><br>*********************************************************** IS CURRENT ***************************************!!! <br>';
                            // echo '*********************************************************** '.$semestry .$s->ID .$s->NAME .$s->SURNAME.' ***************************************!!! <br>';
                        }else{
                            // echo '<br><br><br><br>*********************************************************** NOT CURRENT ***************************************!!! <br>';
                            // echo '*********************************************************** '.$semestry .$s->ID .$s->NAME .$s->SURNAME.' ***************************************!!! <br>';
                            $expstudents[] = [
                                'id' => $s->ID,
                                'lavel' => $i,
                                'name' => $s->NAME,
                                'surname' => $s->SURNAME,
                                'fin_cause' => $s->FIN_CAUSE,
                                'expfin' => true,
                                'activity' => $this->get_activity($s->STD_CODE),
                                'nt_sem' => ($s->NT_SEM != '') ? 'ผ่านแล้ว' : (($s->NT_NOSEM != '') ? 'E-Exam' : 'มีสิทธิ'),
                                'grp_code' => $s->GRP_CODE
                            ];
                        }
                        // $expstudents[] = [
                        //     'id' => $s->ID,
                        //     'lavel' => $i,
                        //     'name' => $s->NAME,
                        //     'surname' => $s->SURNAME,
                        //     'fin_cause' => $s->FIN_CAUSE,
                        //     'expfin' => true,
                        //     'activity' => $this->get_activity($s->STD_CODE),
                        //     'nt_sem' => ($s->NT_SEM != '') ? 'ผ่านแล้ว' : (($s->NT_NOSEM != '') ? 'E-Exam' : 'มีสิทธิ'),
                        //     'grp_code' => $s->GRP_CODE
                        // ];
                    } else {
                        continue;
                    }

                }
            }
        }
        return count($expstudents) !== 0 ? $expstudents : null;
    }
    // ข้อมูลนักศึกษา
    public function get_student($std_code){
        $student = DB::table('student')
        ->where('STD_CODE', $std_code)
        ->select(array('ID', 'STD_CODE', 'NAME', 'SURNAME', 'FIN_CAUSE', 'NT_SEM', 'NT_NOSEM', 'GRP_CODE'))
        ->get();
        return $student;
    }

    // คาดว่าจะจบ
    public function expfin($student_id, $lavel){
        $tgrade = 'grade'.$lavel;
        $subject = 'subjectall';
        $grade = DB::table($tgrade)
        ->join($subject, $tgrade . '.SUB_CODE', '=', $subject.'.SUB_CODE')
        ->where($tgrade . '.STD_CODE', 'LIKE', '1215040001' . $student_id)
        ->where($tgrade . '.GRADE', 'NOT REGEXP', '[ข0ม]')
        ->whereNot(function($query) use ($tgrade) {
            $query->where($tgrade . '.TYP_CODE', 'REGEXP', '7')
                  ->where(function($subQuery) use ($tgrade) {
                      $subQuery->where($tgrade . '.GRADE', 'REGEXP', '0')
                               ->orWhere($tgrade . '.GRADE', '=', null)
                               ->orWhere($tgrade . '.GRADE', '=', ' ');
                  });
        })
        ->select($tgrade . '.STD_CODE', $tgrade . '.SUB_CODE', $tgrade . '.GRADE', $subject.'.SUB_CREDIT')
        ->groupBy($tgrade . '.STD_CODE', $tgrade . '.SUB_CODE', $tgrade . '.GRADE', $subject.'.SUB_CREDIT')
        ->get();
      

        $sum_credit = $grade->sum('SUB_CREDIT');
        // if($student_id == 6512000123 ){
        //     echo '<br><br><br><br><br><br> <br><br>***************************************************  = '. $student_id .' = '.$sum_credit.' ******************************* <br> '.$grade;
        // }

        if (($lavel == 1 && $sum_credit >= 48) || ($lavel == 2 && $sum_credit >= 55) || ($lavel == 3 && $sum_credit >= 76)) {
            return true;
        } else {
            return false;
        }       
    }

    public function current_student($grp_code, $lavel, $semestry) {
        // ตรวจสอบและกำหนดค่าตัวแปร $grp_code
        $grp_code = ($grp_code == '0000' || $grp_code == ' ' || $grp_code == null) ? "[0-9]" : $grp_code;
        // ใช้ตัวแปร $lavel ใน scope ที่ถูกต้อง
        $tgrade = 'grade' . $lavel;
        $student = DB::table($tgrade)
            ->join('student', $tgrade . '.STD_CODE', '=', 'student.STD_CODE')
            ->where(function($query) use ($grp_code, $tgrade) {
                if ($grp_code === "[0-9]") {
                    $query->whereRaw("$tgrade.GRP_CODE REGEXP ?", [$grp_code]);
                } else {
                    $query->where("$tgrade.GRP_CODE", '=', $grp_code);
                }
            })
            ->where("$tgrade.SEMESTRY", '=', $semestry)
            ->select(
                'student.STD_CODE', 
                'student.ID', 
                'student.NAME', 
                'student.SURNAME', 
                'student.FIN_CAUSE', 
                'student.NT_SEM', 
                'student.NT_NOSEM', 
                'student.GRP_CODE'
            )
            ->orderBy('student.ID', 'ASC')
            ->groupBy(
                'student.STD_CODE', 
                'student.ID', 
                'student.NAME', 
                'student.SURNAME', 
                'student.FIN_CAUSE', 
                'student.NT_SEM', 
                'student.NT_NOSEM', 
                'student.GRP_CODE'
            )
            ->get();
        //echo $student;
        return $student;
    }

    public function not_current_student($grp_code, $lavel, $semestry) {
        // $semestry
        $all_semestry = DB::table('grade')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $current_semestry = $all_semestry->first()->SEMESTRY;
        // ตรวจสอบและกำหนดค่าตัวแปร $grp_code
        $grp_code = ($grp_code == '0000' || $grp_code == ' ' || $grp_code == null) ? "[0-9]" : $grp_code;
        // ใช้ตัวแปร $lavel ใน scope ที่ถูกต้อง
        $tgrade = 'grade' . $lavel;

        $student = DB::table($tgrade)
            ->join('student', $tgrade . '.STD_CODE', '=', 'student.STD_CODE')
            ->where(function($query) use ($grp_code, $tgrade) {
                if ($grp_code === "[0-9]") {
                    $query->whereRaw("$tgrade.GRP_CODE REGEXP ?", [$grp_code]);
                } else {
                    $query->where("$tgrade.GRP_CODE", '=', $grp_code);
                }
            })
            //->where("$tgrade.SEMESTRY", '!=', $semestry)
            ->where('student.FIN_SEM', 'NOT REGEXP', '^[0-9]')  
            ->select(
                'student.STD_CODE', 
                'student.ID', 
                'student.NAME', 
                'student.SURNAME', 
                'student.FIN_CAUSE', 
                'student.NT_SEM', 
                'student.NT_NOSEM', 
                'student.GRP_CODE'
            )
            ->orderBy('student.ID', 'ASC')
            ->groupBy(
                'student.STD_CODE', 
                'student.ID', 
                'student.NAME', 
                'student.SURNAME', 
                'student.FIN_CAUSE', 
                'student.NT_SEM', 
                'student.NT_NOSEM', 
                'student.GRP_CODE'
            )
            ->get();

        //echo $student;
        return $student;
    } 
    public function get_activity($std_code){
        $activity = DB::table('activity')
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

}
