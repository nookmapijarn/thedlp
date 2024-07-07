<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamscheduleController extends Controller
{
    //
    protected $lavel;
    protected $std_code;

    public function index()
    {
        // Set
        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $this->std_code = DB::table("student{$this->lavel}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

        $all_semestry = DB::table("grade{$this->lavel}")->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $semestry = $all_semestry->first()->SEMESTRY;
        $grade = $this->get_gradelist($this->std_code, $semestry);
        $schedule = [];
        $student = $this->get_student($this->std_code);
        $nnet = $this->nnet_check($this->std_code, $this->lavel);

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
                                'exam_start' => $this->timeFormatSch($exam_start),
                                'exam_end'  => $this->timeFormatSch($exam_end),
                                'exam_room'  => $g->ROOMNO
                            ]
                        );
        }

        // $key_values = array_column($schedule, 'exam_day'); 
        // array_multisort($key_values, SORT_ASC, $schedule);
        // $key_values2 = array_column($schedule, 'exam_start'); 
        // array_multisort($key_values2, SORT_ASC, $schedule);
        $exam_days = array_column($schedule, 'exam_day');
        $exam_starts = array_column($schedule, 'exam_start');
        // เรียงลำดับตาม 'exam_day' ก่อนแล้วตามด้วย 'exam_start'
        array_multisort($exam_days, SORT_ASC, $exam_starts, SORT_ASC, $schedule);

        // print $schedule[0]['sub_code'];
        // print_r($schedule);
        return view('students.examschedule', compact('schedule', 'nnet', 'student', 'semestry'));
    }

    public function nnet_check($std_code, $lavel){
        if($this->expfin($std_code, $lavel)){
            $student = $this->get_student($std_code);
            foreach ($student as $s) {
                $nnet = ($s->NT_SEM!='' ? 'ผ่านแล้ว' : ($s->NT_NOSEM!='' ? 'E-Exam': 'N-NET'));
            }
            return $nnet;
        }else{
            return null;        
        }
    }

    // ตารางคาดว่าจะจบ
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

    public function get_student($std_code){
        $student = DB::table("student{$this->lavel}")
        ->where('STD_CODE', $std_code)
        ->get();
        return $student;
    }

    public function get_gradelist($std_code, $sumestry){
        // ตาราง garde
        $gradelist = DB::table("grade{$this->lavel}")
        ->where('STD_CODE', $std_code)
        ->where('SEMESTRY', $sumestry)
        ->get();
        return $gradelist;
    }

    public function get_schedule($sub_code, $sumestry){
        $schedule = DB::table("schedule{$this->lavel}")
        ->where('SUB_CODE', $sub_code)
        ->where('SEMESTRY', $sumestry) //'65/2'
        ->orderBy('EXAM_START', 'ASC')
        ->orderBy('EXAM_DAY', 'ASC')
        ->get();
        //echo $schedule[0]->EXAM_DAY;
        if($schedule->count()){
            return $schedule;
        }else{
            return null;
        }
        
    }
    public function get_subject($sub_code){
        $subject = DB::table("subject{$this->lavel}")
        ->where('SUB_CODE', $sub_code)
        ->get();
        return $subject[0]; //ข้อมูลตารางรายวิชา
        //print_r($subject);
        //echo $subject[0]->SUB_NAME;
    }

    public function timeFormatSch($time){
        if(strlen($time)>3){
            $time = substr_replace($time, '.', 2, -3); //16.30
        } else {
            $time = substr_replace($time, '.', 1, -3); //8.30
        }
        return $time;
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
}
