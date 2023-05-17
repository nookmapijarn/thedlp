<?php

namespace App\Http\Controllers;

use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $id = auth()->user()->student_id;
        $student = $this->get_student($id);
        $activity = $this->get_activity($id);
        $gradelist = $this->get_gradelist($id);
        $grade = [];
        
        $act_sum=0;
        foreach ($activity as $p) {
            $act_sum += $p->HOUR;
            //print $p->HOUR;
        }
        $act_percentage = round(($act_sum * 100) / 200, 0);
        //echo "<h1>".$sum."</h1>";

        $semestrylist1 = DB::table('grade')
        ->where('STD_CODE', '1215040001'.$id)
        ->select('SEMESTRY')
        ->distinct() //ข้อมูลไม่ซ้ำ
        ->orderBy('SEMESTRY', 'desc')
        ->get();
        //print_r($semestrylist1[0]);
        //echo "<pre>".($semestrylist1).'</pre>';

         // คำนวนหน่วยกิต
         $credit = $this->cal_credit()['CREDIT'];
         $allcredit = $this->cal_credit()['ALL_CREDIT'];
         $credit_percent = round(($credit * 100) / $allcredit,0);
        //  เกรดเฉลี่ย และ การเข้าสอบ
         $grade_avg = $this->grade_avg();
         $exam_avg = $this->exam_avg();
         $nnet = $student[0]->ABLEVEL2 == 1 ? 'เข้ารับ' : 'ไม่เข้ารับ';
         $moral = 'ดี';
         switch ($student[0]->ABLEVEL1) {
            case 0:
                $moral = 'ปรับปรุง';
              break;
            case 1:
                $moral = 'พอใช้';
              break;
            case 2:
                $moral = 'ดี';
               break;
            case 3:
                $moral = 'ดีมาก';
              break;
          }
         
          foreach ($gradelist as $g) {
            array_push($grade, 
                            [
                                'sub_code' => $g->SUB_CODE, 
                                'sub_name' => $this->getSubject($g->SUB_CODE)->SUB_NAME,
                                'grade' => $g->GRADE,
                                'semestry' => $g->SEMESTRY,
                            ]
                        );
        }

         //print $student[0]->ID;

        return view('dashboard', compact(   'grade',
                                            'semestrylist1', 
                                            'student',
                                            'act_sum',
                                            'act_percentage',
                                            'credit',
                                            'allcredit',
                                            'credit_percent',
                                            'grade_avg',
                                            'exam_avg',
                                            'moral',
                                            'nnet'
                                        ));
    }

    public function get_student($id){
        $student = DB::table('student')
        ->where('STD_CODE', '1215040001'.$id)
        ->get();
        return $student;
    }

    public function get_activity($id){
        $activity = DB::table('activity')
        ->where('STD_CODE', '1215040001'.$id)
        ->get();
        //print_r($activity[1]);     
        return $activity;
    }
    public function lavelis(){
        // ดูระดับชั้น
        $str = auth()->user()->student_id;
        $split = str_split($str, 1);
        //Print_r($split);
        return $split[3];
    }

    // คำนวนเกรดเฉลี่ย
    public function grade_avg(){
        $gradelist = $this->get_gradelist();
        $grade = 0;
        $all_grade = 0;
        $grade_avg = 0;
        foreach ($gradelist as $g) {
            // เอาเฉพาะที่มีเกรด ตรวจค่าตัวเลข และไม่เท่ากับ 0
            if(is_numeric($g->GRADE) && $g->GRADE != 0){   
                // +หน่วยกิต         
                $grade += $g->GRADE;
                $all_grade++;
            }
        }
        if($all_grade!=0){$grade_avg = round($grade/$all_grade,2);}       
        return $grade_avg;
    }

    // ค่าเฉลี่ยการเข้าสอบ
    public function exam_avg(){
        $gradelist = $this->get_gradelist();
        $exam = 0; // จำนวนเข้า
        $exam_all = count($gradelist); // เกรดทั้งหมด
        $exam_avg = 0; // ค่าเฉลี่ยเข้าสอบ
        foreach ($gradelist as $g) {
            // เอาเฉพาะขาดสอบ
            if(is_numeric($g->GRADE)){   
                $exam++;
            }
        }
        //print $exam_all;
        $exam_avg = round(($exam/$exam_all)*100, 2);
        return $exam_avg;
    }

    public function get_gradelist(){
        // ตาราง garde
        $gradelist = DB::table('grade')
        ->where('STD_CODE', '1215040001'.$this->getUserid())
        ->get();
        return $gradelist;
    }
    public function cal_credit(){

        $gradelist = $this->get_gradelist();
        $credit=0; // หน่วยกิตทีมี
        $allcredit=0; // หน่วยกิตที่ต้องเรียน

        foreach ($gradelist as $g) {
            // เอาเฉพาะที่มีเกรด ตรวจค่าตัวเลข และไม่เท่ากับ 0
            if(is_numeric($g->GRADE) && $g->GRADE != 0){   
                // +หน่วยกิต         
                $credit += $this->getSubject($g->SUB_CODE)->SUB_CREDIT;
            }
        }

        switch ($this->lavelis()) {
            case "3":
                $allcredit = 76;
              break;
            case "2":
                $allcredit = 57;
              break;
            case "1":
                $allcredit = 48;
              break;
          }

          return ['CREDIT'=>$credit, 'ALL_CREDIT'=>$allcredit];
    }

    public function getSubject($sub_code){
        $subject = DB::table('subject')
        ->where('SUB_CODE', $sub_code)
        ->get();
        return $subject[0]; //ข้อมูลตารางรายวิชา
        //print_r($subject);
        //echo $subject[0]->SUB_NAME;
    }

    public function getUserid(){
        $id = auth()->user()->student_id;
        return $id;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
