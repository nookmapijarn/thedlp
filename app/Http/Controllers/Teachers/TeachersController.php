<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class TeachersController extends Controller
{
    protected $semestry = '66/1';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //
        $data=[];
        $tumbon = str_split($request->tumbon, 4)[0];
        $studreport = $request->studreport;

        switch ($studreport) {
            case 'นักศึกษาทั้งหมด':
                $data = $this->allstudent($tumbon);
                $data = collect($data)->sortBy('lavel')->toArray(); // $mystudent = collect($mystudent)->sortBy('lavel')->reverse()->toArray(); DESC
                return view('teachers.tdashboard' ,compact('data'));
              break;
            case 'เฉพาะผู้คาดว่าจะจบ':
                $data = $this->expstudent($tumbon, $studreport);
                $data = collect($data)->sortBy('lavel')->toArray();
                return view('teachers.tdashboard' ,compact('data'));
              break;
            default:
              return view('teachers.tdashboard' ,compact('data'));
          }                       
        //return view('teachers.tdashboard' ,compact('data'));
    }

    public function allstudent($grp_code){
        $current_student = $this->current_student($grp_code);
        $allstudent = [];

        foreach ($current_student as $g) {
            $student = $this->get_student($g->STD_CODE);
            foreach ($student as $s){

                switch ($this->expfin($s->ID)) {
                    case true :
                      $expfin = 'คาดว่าจะจบ';
                      $nnet = ($s->NT_SEM!='' ? 'เข้ารับแล้ว' : ($s->NT_NOSEM!='' ? 'E-Exam': 'ยังไม่เข้ารับ'));
                      break;
                    case false:
                      $expfin = '-';
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
                        'expfin'    =>  $expfin,
                        'activity'  =>  $this->get_activity($s->STD_CODE),
                        'nt_sem'    =>  $nnet

                    ]
                );
            }
        }
        
        return $allstudent;        
    }

    public function expstudent($grp_code){
        $current_student = $this->current_student($grp_code);
        $expstudent = [];

        foreach ($current_student as $g) {
            $student = $this->get_student($g->STD_CODE);
            foreach ($student as $s){

                switch ($this->expfin($s->ID)) {
                    case true :
                      $expfin = '/';
                      $nnet = ($s->NT_SEM!='' ? 'เข้ารับแล้ว' : ($s->NT_NOSEM!='' ? 'E-Exam': 'ยังไม่เข้ารับ'));
                      array_push($expstudent, 
                        [
                            'id'        =>  $s->ID,
                            'lavel'     =>  $this->lavelis($s->STD_CODE),  
                            'name'      =>  $s->NAME,
                            'surname'   =>  $s->SURNAME,
                            'expfin'    =>  $expfin,
                            'activity'  =>  $this->get_activity($s->STD_CODE),
                            'nt_sem'    =>  $nnet
    
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
        
        return $expstudent;        
    }

    // ข้อมูลนักศึกษา
    public function get_student($std_code){
        $student = DB::table('student')
        ->where('STD_CODE', $std_code)
        ->select(array('ID', 'STD_CODE', 'NAME', 'SURNAME', 'NT_SEM', 'NT_NOSEM'))
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

    public function current_student($grp_code){
        // ตาราง garde
        $grade = DB::table('grade')
        ->where('GRP_CODE', $grp_code)
        ->where('SEMESTRY', $this->semestry)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')
        ->groupBy('STD_CODE')
        ->get();
        
        $mygreade = array();
        foreach ($grade as $g) {
            array_push($mygreade, 
                            [
                                'std_code' => $g->STD_CODE, 
                            ]
                        );
        }
        return $grade;
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
