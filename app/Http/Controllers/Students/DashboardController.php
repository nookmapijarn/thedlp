<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RoleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $lavel;
    protected $std_code;

    public function index()
    {
        // SET
        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $lavel = $this->lavel;
        $this->std_code = DB::table("student{$this->lavel}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

        $student = $this->get_student($this->std_code, $this->lavel);
        $activity = $this->get_activity($this->std_code, $this->lavel);
        $gradelist = $this->get_gradelist($this->std_code, $this->lavel);
        $grade = [];
        $grade_analyze = $this->get_grade_analyze($this->lavel);
        
        if (Count($student)==0) {
            //Auth::logout();
            // return redirect('/dashboard')->with('status', 'Profile updated!');
            $role = auth()->user()->role;
            return redirect()->to("welcome?roletype={$role}&studentnull=true");
        }

        $act_sum=0;
        foreach ($activity as $p) {
            $act_sum += $p->HOUR;
            //print $p->HOUR;
        }
        $act_percentage = round(($act_sum * 100) / 200, 0);
        //echo "<h1>".$sum."</h1>";

        $semestrylist1 = DB::table("grade{$this->lavel}")
        ->where('STD_CODE', $this->std_code)
        ->select('SEMESTRY')
        ->distinct() //ข้อมูลไม่ซ้ำ
        ->orderBy('SEMESTRY', 'ASC')
        ->get();

        //print_r($semestrylist1[0]);
        //echo "<pre>".($semestrylist1).'</pre>';

         // คำนวนหน่วยกิต
         $credit = $this->cal_credit($gradelist, $this->lavel)['CREDIT'];
         $allcredit = $this->cal_credit($gradelist, $this->lavel)['ALL_CREDIT'];
         if($allcredit!=0||$allcredit!=null){
            $credit_percent = round(($credit * 100) / $allcredit,0);
         }else{
            $credit_percent = 0;
         }
         
         
        //  เกรดเฉลี่ย ระยะเวลาเรียน และ การเข้าสอบ
         $grade_avg = $this->grade_avg($gradelist);
         $exam_avg = $this->exam_avg($gradelist);
         $timelerning = $this->timelerning($this->lavel);
         $nnet = null;
         $moral = null;

         if(Count($student)){
            $nnet = $student[0]->ABLEVEL2 == 1 ? 'ผ่าน' : '-';  
            $moral = $student[0]->ABLEVEL1;         
         }


         
          foreach ($gradelist as $g) {
            array_push($grade, 
                            [
                                'sub_code' => $g->SUB_CODE, 
                                'sub_name' => $this->getSubject($g->SUB_CODE, $this->std_code)->SUB_NAME,
                                'grade' => $g->GRADE,
                                'semestry' => $g->SEMESTRY,
                            ]
                        );
        }

         //print $student[0]->ID;

        return view('students.dashboard', compact(   'grade',
                                            'lavel',
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
                                            'nnet',
                                            'grade_analyze',
                                            'timelerning'
                                        ));
    }

    public function get_student(){

        $student = DB::table("student{$this->lavel}")
        ->where('STD_CODE', $this->std_code)
        ->get();
        return $student;
    }

    public function get_activity(){
        $activity = DB::table("activity{$this->lavel}")
        ->where('STD_CODE', $this->std_code)
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
    public function timelerning(){
        // ระยะเวลาเรียน
        $semtime = DB::table("grade{$this->lavel}")
        ->where('STD_CODE', $this->std_code)
        ->select('SEMESTRY')
        ->distinct() //ข้อมูลไม่ซ้ำ
        ->orderBy('SEMESTRY', 'ASC')
        ->get();

        return $semtime->Count()*10; // มี 10 ภาคเรียน *10 เพื่อคืนค่าเป็นร้อยละ
    }
    // คำนวนเกรดเฉลี่ย
    public function grade_avg($gradelist){
        //$gradelist = $this->get_gradelist($id, $lavel);
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
    public function exam_avg($gradelist){
        $exam = 0; // จำนวนเข้า
        $exam_all = count($gradelist); // เกรดทั้งหมด
        $exam_avg = 0; // ค่าเฉลี่ยเข้าสอบ
        foreach ($gradelist as $g) {
            // เอาเฉพาะขาดสอบ
            if(is_numeric($g->GRADE)){   
                $exam++;
            }
        }
        if($exam_all!=0){
        //print $exam_all;
        $exam_avg = round(($exam/$exam_all)*100, 2);
        }
        return round($exam_avg, 0);
    }

    public function get_gradelist(){
        // ตาราง grade
        $gradelist = DB::table("grade{$this->lavel}")
        ->where('STD_CODE', $this->std_code)
        ->get();
        return $gradelist;
    }

    public function get_grade_analyze(){
        $id = $this->getStudentidByUser();
        $learning   = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^ทร')->get();
        $besic      = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^พ')->get();
        $career     = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^อ')->get();
        $life       = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^ทช')->get();
        $society    = DB::table("grade{$this->lavel}")->where('STD_CODE', $this->std_code)->where('GRADE', '!=', 'ข')->where('GRADE', '!=', '')->where('SUB_CODE', 'regexp', '^ส')->get();
        
        $learning   = ($learning->Count()!=0) ? $learning->sum('TOTAL')/$learning->Count() : 0;
        $besic      = ($besic->Count()!=0) ? $besic->sum('TOTAL')/$besic->Count() : 0;
        $career     = ($career->Count()!=0) ? $career->sum('TOTAL')/$career->Count() : 0;
        $life       = ($life->Count()!=0) ? $life->sum('TOTAL')/$life->Count() : 0;
        $society    = ($society->Count()!=0) ? $society->sum('TOTAL')/$society->Count() : 0;
        //echo $society->sum('TOTAL')/$society->Count();
        //echo $society;
        return [round($learning, 2), round($besic, 2), round($career, 2), round($life, 2), round($society, 2)];
    }

    public function cal_credit($gradelist){

        // $gradelist = $this->get_gradelist($lavel);
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
                'SUB_CREDIT' => 0
            ]));
        }
        
    }

    public function getStudentidByUser(){
        $id = auth()->user()->student_id;
        return $id;
    }
}
