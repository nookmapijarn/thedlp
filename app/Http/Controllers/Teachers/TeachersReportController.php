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
        $id = auth()->user()->student_id;

        if ($id != '1215040001') {
            return redirect('welcome/?roletype='.$id);
        }

        // test
        // $grade = DB::table('grade')
        // ->where('GRP_CODE', 'regexp', $tumbon)
        // ->where('SEMESTRY', $this->semestry)
        // ->select('STD_CODE')
        // ->orderBy('STD_CODE', 'ASC')
        // ->groupBy('STD_CODE')
        // ->get();
        // echo $grade;
        // endtest

        if($request->tumbon!=''){
            $grp_code = str_split($request->tumbon, 4)[0];
            $semestry = $request->semestry;
            $tumbon = $request->tumbon;
            // if($grp_code != '0000'){
            //     $tumbon = $grp_code;
            // }else{
            //     $tumbon = '[0-9]';
            // }
            $studreport = $request->studreport;
        }else{
            //$tumbon = '[0-9]';
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
                $data = $this->expstudent($tumbon, $studreport, $semestry);
                $data = collect($data)->sortBy('lavel')->toArray();
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
              break;
            case 'ไม่จบตกค้าง(ที่ไม่ได้ลงทะเบียนแล้ว)':
                $data = $this->unfinishstudent($tumbon, $studreport, $semestry);
                $data = collect($data)->sortBy('lavel')->toArray();
                $data = count($data) !== 0 ? $data :  null;
                return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
              break;
            default:
              return view('teachers.treport' ,compact('data', 'semestry', 'tumbon', 'all_tumbon', 'all_semestry'));
          }                       
        //return view('teachers.tdashboard' ,compact('data'));
    }

    public function allstudent($grp_code, $semestry){
        $current_student = $this->current_student($grp_code, $semestry);
        $allstudent = [];

        foreach ($current_student as $g) {
            $student = $this->get_student($g->STD_CODE);
            foreach ($student as $s){

                switch ($this->expfin($s->ID)) {
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
                        'lavel'     =>  $this->lavelis($s->STD_CODE),  
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

    public function expstudent($grp_code, $semestry){
        $current_student = $this->current_student($grp_code, $semestry);
        $expstudent = [];

        foreach ($current_student as $g) {
            $student = $this->get_student($g->STD_CODE);
            foreach ($student as $s){

                switch ($this->expfin($s->ID)) {
                    case true :
                      $expfin = 1;
                      $nnet = ($s->NT_SEM!='' ? 'ผ่านแล้ว' : ($s->NT_NOSEM!='' ? 'E-Exam': 'มีสิทธิ'));
                      array_push($expstudent, 
                        [
                            'id'        =>  $s->ID,
                            'lavel'     =>  $this->lavelis($s->STD_CODE),  
                            'name'      =>  $s->NAME,
                            'surname'   =>  $s->SURNAME,
                            'fin_cause' =>  $s->FIN_CAUSE,
                            'expfin'    =>  $expfin,
                            'activity'  =>  $this->get_activity($s->STD_CODE),
                            'nt_sem'    =>  $nnet,
                            'grp_code'  =>  $s->GRP_CODE
                        ]
                      );
                      break;
                    case false:
                      break;
                    default:
                        $expfin = '*';
                        $nnet = '*';
                  }
            }
        }
        $expstudent = count($expstudent) !== 0 ? $expstudent :  null;
        return $expstudent;        
    }

    public function unfinishstudent($grp_code){
        $current_student = $this->all_student($grp_code);
        $expstudent = [];

        foreach ($current_student as $g) {
            $student = $this->get_student($g->STD_CODE);
            foreach ($student as $s){

                switch ($this->exp_unfin($s->ID)) {
                    case true :
                      $expfin = 1;
                      $nnet = ($s->NT_SEM!='' ? 'ผ่านแล้ว' : ($s->NT_NOSEM!='' ? 'E-Exam': 'มีสิทธิ'));
                      array_push($expstudent, 
                        [
                            'id'        =>  $s->ID,
                            'lavel'     =>  $this->lavelis($s->STD_CODE),  
                            'name'      =>  $s->NAME,
                            'surname'   =>  $s->SURNAME,
                            'fin_cause' =>  $s->FIN_CAUSE,
                            'expfin'    =>  $expfin,
                            'activity'  =>  $this->get_activity($s->STD_CODE),
                            'nt_sem'    =>  $nnet,
                            'grp_code'  =>  $s->GRP_CODE
                        ]
                      );
                      break;
                    case false:
                      break;
                    default:
                        $expfin = '*';
                        $nnet = '*';
                  }
            }
        }
        $expstudent = count($expstudent) !== 0 ? $expstudent :  null;
        return $expstudent;        
    }
    // ข้อมูลนักศึกษา
    public function get_student($std_code){
        $student = DB::table('student')
        ->where('STD_CODE', $std_code)
        ->select(array('ID', 'STD_CODE', 'NAME', 'SURNAME', 'FIN_CAUSE', 'NT_SEM', 'NT_NOSEM', 'GRP_CODE'))
        ->get();
        return $student;
    }

    // ตารางคาดว่าจะจบ
    public function expfin($student_id){
        $expfin = DB::table('expectfin')->where('ID', $student_id)->first();
        if($expfin === null){
            return false;
        } else {
            return true;
        }       
    }

    // ตารางไม่จบตกค้าง
    public function exp_unfin($student_id){
        $expfin = DB::table('expectfin1')->where('ID', $student_id)->first();
        if($expfin === null){
            return false;
        } else {
            return true;
        }       
    }
    public function current_student($grp_code, $semestry){
        // ตาราง garde
        $grade = DB::table('grade')
        ->where('GRP_CODE', 'regexp', $grp_code)
        ->where('SEMESTRY', $semestry)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')  
        ->groupBy('STD_CODE')
        ->get();
        
        return $grade;
    }

    public function all_student($grp_code){
        // ตาราง garde
        $student = DB::table('student')
        ->where('GRP_CODE', 'regexp', $grp_code)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')
        ->groupBy('STD_CODE')
        ->get();
        
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
