<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class BossController extends Controller
{
    //
    protected $semestry = '66/1';

    public function index(Request $request)
    {
        //echo $this->student_primary($this->semestry, '1');
       $agent = new Agent();
       if( $agent->isMobile()){
        $labels = $this->get_semestry(8);
       }else{
        $labels = $this->get_semestry(20);
       }   
        
        $data_student = [];
        $data_new_student = [];
        $data_finish_student = [];
        $data_studentPrimary = [];
        $data_studentJunior  = [];
        $data_studentSenior  = [];

        // echo $this->finish_student('65/2')->Count()."SEMMMMMMM";

        $index = 0;
        foreach($labels as $key=>$val) {
            // Use $key as an index, or...
            $allstudent = $this->current_student($val)->Count();
            $studentPrimary = $this->student_primary($val, '1')->Count();
            $studentJunior = $this->student_primary($val, '2')->Count();
            $studentSenior = $this->student_primary($val, '3')->Count();
            $new_student = $this->new_student($val)->Count();
            $finish_student = $this->finish_student($val)->Count();

            array_push($data_student, $allstudent);
            array_push($data_studentPrimary, $studentPrimary);
            array_push($data_studentJunior, $studentJunior);
            array_push($data_studentSenior, $studentSenior);
            array_push($data_new_student, $new_student);
            array_push($data_finish_student, $finish_student);
            // ... manage the index this way..
            //echo "Index is $index\n ".' Value ='.$val;
            $index++;
        }

        // Boss
        $semestry = $this->semestry;
        $allstudent = $this->current_student($this->semestry)->Count();

        $exam_avg = $this->exam_avg('65/2')['result'];
        $exam_avg_semestry = $this->exam_avg('65/2')['semestry'];

        $new_student = $this->new_student($semestry)->Count();
        $expectfin_student = $this->expectfin_student()->Count();
        $nofinish_student = $this->nofinish_student()->Count();
        
        return view('boss.bdashboard',compact('allstudent', 
                                                'exam_avg',   
                                                'semestry', 
                                                'exam_avg_semestry', 
                                                'new_student',
                                                'expectfin_student',
                                                'nofinish_student',
                                                'labels', 
                                                'data_student',
                                                'data_studentPrimary',
                                                'data_studentJunior',
                                                'data_studentSenior',
                                                'data_new_student',
                                                'data_finish_student'
                                            ));
    }

    public function current_student($semestry=''){
        $g = DB::table('grade')
        ->where('SEMESTRY', $semestry)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')
        ->groupBy('STD_CODE')
        ->get();
        return $g;
    }

    public function get_semestry($limit){
        $sem = DB::table('grade')
        ->select('SEMESTRY')
        ->orderBy('SEMESTRY', 'DESC')
        ->groupBy('SEMESTRY')
        ->take($limit)
        ->get();

        $isem = [];

        foreach($sem as $s){
            array_push($isem, $s->SEMESTRY);
        }
        return  array_reverse($isem);
    }

    public function student_primary($semestry, $tlavel){
        //$ID = str_replace('/','',$semestry);
        $s = DB::table('grade'.$tlavel)
        ->where('SEMESTRY', $semestry)
        ->select('STD_CODE')
        ->groupBy('STD_CODE')
        //->where('STD_CODE', 'regexp', '1215040001'.$ID.'[0-9]+')
        //->where('ID', '>', $ID)
        ->get();
        return $s;
    }
    public function new_student($semestry){
        $ID = str_replace('/','',$semestry); //661
        $s = DB::table('student')
        ->where('ID', 'regexp', $ID.'[0-9]')
        ->select('ID')
        ->orderBy('ID', 'ASC')
        ->groupBy('ID')
        ->get();
        return $s;
    }

    public function finish_student($semestry){
        $semestry = strval($semestry);
        $s = DB::table('student')
        ->where('FIN_SEM', '!=', "")
        ->where('FIN_SEM', 'regexp', $semestry)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')
        ->groupBy('STD_CODE')
        ->get();
        //echo $s;
        return $s;
    }

    public function expectfin_student(){
        $expectfin = DB::table('expectfin')
        ->select('ID')
        ->orderBy('ID', 'ASC')
        ->groupBy('ID')
        ->get();
        return $expectfin;
    }

    public function nofinish_student(){
        $nofinish_student = DB::table('expectfin1')
        ->select('ID')
        ->orderBy('ID', 'ASC')
        ->groupBy('ID')
        ->get();
        return $nofinish_student;
    }
    public function exam_avg($semestry){
        $exam_grade = DB::table('grade')
        ->where('SEMESTRY', $semestry)
        ->where('GRADE', '!=', 'ข')
        ->where('GRADE', '!=', '')
        ->select('STD_CODE')
        ->groupBy('STD_CODE')
        ->get();

        $all_grade = DB::table('grade')
        ->where('SEMESTRY', $semestry)
        ->select('STD_CODE')
        ->groupBy('STD_CODE')
        ->get();

        // echo 'All ->'.$all_grade->Count().'<br>';
        // echo 'g ->'.$exam_grade->Count().'<br>';
        if($exam_grade->Count()!=0){
            $result = ($exam_grade->Count()*100)/$all_grade->Count();
            return [
                'result'   => round($result, 2), 
                'semestry' => $semestry
            ];
        }else{
            return 0;
        }
         
    }
}
