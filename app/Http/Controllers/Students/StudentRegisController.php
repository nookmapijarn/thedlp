<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentRegisController extends Controller
{

    protected $lavel;
    protected $std_code;

    public function index(){

        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $this->std_code = DB::table("student{$this->lavel}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

        $all_semestry = DB::table("grade{$this->lavel}")->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $semestry = $all_semestry->first()->SEMESTRY;
        
        $learned = $this->learned()['learned'];
        $allsub = $this->getAllSubject();
        $sumcredit = $this->learned()['sumcredit'];

        $current_regis =  $this->get_current_regis($semestry);
        $notlearned = $allsub;

        foreach ($notlearned as $key => $a){
            foreach($current_regis as $c){
                if($a['sub_code'] == $c->SUB_CODE){
                    //echo ' UNSET Current  ! => '.$notlearned[$key]['sub_code'];
                    unset($notlearned[$key]);
                }
            }
            foreach($learned as $l){
                if($a['sub_code'] == $l['sub_code']){
                    //echo ' UNSET Learned ! => '.$notlearned[$key]['sub_code'];
                    unset($notlearned[$key]);
                }
            }
        }

        foreach ($learned as $l) {           
            if(in_array($l, $allsub)){
                $unsetkey = array_search($l,$allsub);
                unset($allsub[$unsetkey]);
            }
        }
        //print_r($notlearned);
        return view('students.studentregis' ,compact('learned', 'notlearned', 'sumcredit', 'current_regis', 'semestry'));
    }

    // หาวิชาที่เรียนแล้ว
    public function learned(){
        $gradelist = $this->get_gradelist();
        $learned = array();
        $sumcredit = 0;
        foreach ($gradelist as $g) {
            // เอาเฉพาะที่มีเกรด ตรวจค่าตัวเลข และไม่เท่ากับ 0
            if(is_numeric($g->GRADE) && $g->GRADE != 0){   
                $credit = $this->getSubject($g->SUB_CODE)->SUB_CREDIT;
                $sumcredit += $credit;
                array_push($learned, 
                [
                    'sub_code'   => $g->SUB_CODE, 
                    'sub_name'   => $this->getSubject($g->SUB_CODE)->SUB_NAME, 
                    'sub_type'   => $this->getSubject($g->SUB_CODE)->SUB_TYPE,
                    'sub_credit' => ($credit) ? $credit:'ไม่มีข้อมูล',
                    'grade' => $g->GRADE,
                ]
                );
            }
        }

        return [
                'learned' => $learned,
                'sumcredit' => $sumcredit
            ];
    }

    public function getAllSubject(){
        $student_lavel = $this->StudentLavelis();
        $subject = DB::table("subject{$this->lavel}")->get();
        $subject_user_level = array();
        foreach ($subject as $s) {
            $subject_level = $this->SubCodeLavel($s->SUB_CODE);
            if($student_lavel == $subject_level){   
                array_push($subject_user_level, 
                [
                    'sub_code' => $s->SUB_CODE, 
                    'sub_name' => $s->SUB_NAME,
                    'sub_type' => $s->SUB_TYPE,
                    'sub_credit' => $s->SUB_CREDIT,
                ]
                );
            }
        }

        return $subject_user_level; //ข้อมูลตารางรายวิชา
    }

    public function StudentLavelis(){
        // ดูระดับชั้น
        $str = auth()->user()->student_id;
        $split = str_split($str, 1);
        //Print_r($split);
        return $split[3];
    }

    public function SubCodeLavel($code){
        // ดู Subcode แยก array อยู่ตำแหน่งที่ 6
        $str = $code;
        $split = str_split($str, 1); // SUB_CODE = Array ( [0] => � [1] => � [2] => � [3] => � [4] => � [5] => � [6] => 1 [7] => 1 [8] => 0 [9] => 0 [10] => 1 )
        //Print_r($split);
        return $split[6];
    }

    public function get_gradelist(){
        // ตาราง garde
        $gradelist = DB::table("grade{$this->lavel}")
        ->where('STD_CODE', $this->std_code)
        ->get();
        return $gradelist;
    }

    public function get_current_regis($semestry){
        // ตาราง garde
        $tsubject = "subject{$this->lavel}";
        $tgrade = "grade{$this->lavel}";

        $list = DB::table($tgrade)
        ->where('STD_CODE', $this->std_code)
        ->where('SEMESTRY', $semestry)
        ->join($tsubject, $tgrade.'.SUB_CODE', '=', $tsubject.'.SUB_CODE')
        ->select($tsubject.'.SUB_CODE', $tsubject.'.SUB_NAME', $tsubject.'.SUB_CREDIT', $tsubject.'.SUB_TYPE', $tgrade.'.GRADE')
        ->get();
        return $list;
    }

    public function getStudentidByUser(){
        $id = auth()->user()->student_id;
        return $id;
    }

    public function getSubject($sub_code){
        // echo "sub_code : ".$sub_code;
        $subject = DB::table("subject{$this->lavel}")
        ->where('SUB_CODE', $sub_code)
        ->get();
        // print_r($subject);
        // echo $subject[0]->SUB_NAME;
        if($subject->isNotEmpty()){
            return $subject[0]; //ข้อมูลตารางรายวิชา
        } else {
            return json_decode(json_encode([
                'SUB_NAME' => '**** ไม่มีข้อมูลในตารางรายยวิชา ****',
                'SUB_TYPE' => null,
                'SUB_CREDIT' => 0
            ]));
        }
    }

}
