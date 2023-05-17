<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamscheduleController extends Controller
{
    //
    public function index()
    {
        $id = auth()->user()->student_id;
        $grade = $this->get_gradelist($id);
        $schedule = [];

        foreach ($grade as $g) {
            $exam_day = 0;
            $exam_start = 0;
            $exam_end = 0;
            if($this->get_schedule($g->SUB_CODE, $g->SEMESTRY)!=null){
                $exam_day = $this->get_schedule($g->SUB_CODE, $g->SEMESTRY)[0]->EXAM_DAY;
                $exam_start = $this->get_schedule($g->SUB_CODE, $g->SEMESTRY)[0]->EXAM_START;
                $exam_end = $this->get_schedule($g->SUB_CODE, $g->SEMESTRY)[0]->EXAM_END;
            }

            array_push($schedule, 
                            [
                                'sub_code' => $g->SUB_CODE, 
                                'sub_name' => $this->get_subject($g->SUB_CODE)->SUB_NAME,
                                'exam_day' => $exam_day,
                                'exam_start' => $exam_start,
                                'exam_end'  => $exam_end,
                                'exam_room'  => $g->ROOMNO
                            ]
                        );
        }
        // print $schedule[0]['sub_code'];
        // print_r($schedule);
        return view('examschedule', compact('schedule'));
    }

    public function get_student($id){
        $student = DB::table('student')
        ->where('STD_CODE', '1215040001'.$id)
        ->get();
        return $student;
    }

    public function get_gradelist($id){
        // ตาราง garde
        $gradelist = DB::table('grade')
        ->where('STD_CODE', '1215040001'.$id)
        ->where('SEMESTRY', '64/1')
        ->get();
        return $gradelist;
    }

    public function get_schedule($sub_code, $sumestry){
        $schedule = DB::table('schedule')
        ->where('SUB_CODE', $sub_code)
        ->where('SEMESTRY', $sumestry) //'65/2'
        ->orderBy('EXAM_DAY', 'ASC')
        ->orderBy('EXAM_START', 'ASC')
        ->get();
        //echo $schedule[0]->EXAM_DAY;
        if($schedule->count()){
            return $schedule;
        }else{
            return null;
        }
        
    }
    public function get_subject($sub_code){
        $subject = DB::table('subject')
        ->where('SUB_CODE', $sub_code)
        ->get();
        return $subject[0]; //ข้อมูลตารางรายวิชา
        //print_r($subject);
        //echo $subject[0]->SUB_NAME;
    }
}
