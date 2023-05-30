<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BossController extends Controller
{
    //
    protected $semestry = '66/1';

    public function index(Request $request)
    {
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
                                                'nofinish_student'
                                            ));
    }

    public function current_student($semestry){
        $g = DB::table('grade')
        ->where('SEMESTRY', $semestry)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')
        ->groupBy('STD_CODE')
        ->get();
        return $g;
    }

    public function new_student($semestry){
        $g = DB::table('student')
        ->where('ID', '>', $semestry)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')
        ->groupBy('STD_CODE')
        ->get();
        return $g;
    }

    public function expectfin_student(){
        $expectfin = DB::table('expectfin')
        ->get();
        return $expectfin;
    }

    public function nofinish_student(){
        $nofinish_student = DB::table('expectfin1')
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
