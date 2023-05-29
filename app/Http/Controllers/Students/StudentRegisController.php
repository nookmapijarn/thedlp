<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentRegisController extends Controller
{
    //
    public function index(){
        $learned = $this->learned();
        $notlearned = $this->getAllSubject();

        foreach ($learned as $l) {           
            if(in_array($l, $notlearned)){
                $unsetkey = array_search($l,$notlearned);
                unset($notlearned[$unsetkey]);
            }
        }
        //print_r($notlearned);
        return view('students.studentregis' ,compact('learned', 'notlearned'));
    }

    // หาวิชาที่เรียนแล้ว
    public function learned(){
        $gradelist = $this->get_gradelist();
        $learned = array();
        foreach ($gradelist as $g) {
            // เอาเฉพาะที่มีเกรด ตรวจค่าตัวเลข และไม่เท่ากับ 0
            if(is_numeric($g->GRADE) && $g->GRADE != 0){   
                array_push($learned, 
                [
                    'sub_code'   => $g->SUB_CODE, 
                    'sub_name'   => ($this->getSubject($g->SUB_CODE)!=null) ? $this->getSubject($g->SUB_CODE)->SUB_NAME:'ไม่มีข้อมูล', 
                    'sub_type'   => ($this->getSubject($g->SUB_CODE)!=null) ? $this->getSubject($g->SUB_CODE)->SUB_TYPE:'ไม่มีข้อมูล',
                    'sub_credit' => ($this->getSubject($g->SUB_CODE)!=null) ? $this->getSubject($g->SUB_CODE)->SUB_CREDIT:'ไม่มีข้อมูล',
                    'grade' => $g->GRADE,
                ]
                );
            }
        }

        return $learned;
    }

    public function getAllSubject(){
        $student_lavel = $this->StudentLavelis();
        $subject = DB::table('subject')->get();
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
        $gradelist = DB::table('grade')
        ->where('STD_CODE', '1215040001'.$this->getStudentidByUser())
        ->get();
        return $gradelist;
    }

    public function getStudentidByUser(){
        $id = auth()->user()->student_id;
        return $id;
    }

    public function getSubject($sub_code){
        $subject = DB::table('subject')
        ->where('SUB_CODE', $sub_code)
        ->first();
        //print_r($subject);
        if($subject){
            //echo '<br><br><br>'.$subject->SUB_CODE;
            return $subject; //ข้อมูลตารางรายวิชา
        }else{
            //echo '<br><br><br> ไม่มีวิชานี้'.$sub_code;
            return null;
        }
    }

}
