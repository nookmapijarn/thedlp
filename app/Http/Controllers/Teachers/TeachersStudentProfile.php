<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachersStudentProfile extends Controller
{
    //
    public function index(Request $request)
    {

        //
        $student_id = null;
        $student_data = null;
        $student_data = [];
        $grade_data = [];
        $activity_data = [];
        $sum_grade = 0;
        $sum_act = 0;

        if($request->student_id!=null){

            $request->validate([
                'student_id' => ['numeric', 'digits:10', 'digits:10'],
            ],[
                'student_id.numeric' => 'ตัวเลขเท่านั้น',
                'student_id.digits' => 'รหัสต้องมี 10 หลัก',
                'student_id.digits' => 'รหัสต้องมี 10 หลัก',
            ]);

            $student_id = $request->student_id;
            $lavel = str_split($student_id, 1)[3]; //ตำแหน่งที่ 3 ของ ID คือ ระดับชั้น

            if($lavel >= 1 && $lavel <= 3){
                $std_code = DB::table("student{$lavel}")->where('ID', $student_id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

            } else {
                return view('teachers.tstudentprofile' ,compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act'));
            }

            $tgrade = 'grade'.$lavel;
            $tstudent = 'student'.$lavel;
            $tsubject = 'subject'.$lavel;
            $tactivity = 'activity'.$lavel;


            $student_data = DB::table($tstudent)
            ->where('STD_CODE', '=', $std_code)
            ->get();

            $grade_data = DB::table($tgrade)
            ->where('STD_CODE', '=', $std_code)
            ->where('GRADE', '>', 0)
            ->join($tsubject, $tgrade.'.SUB_CODE', '=', $tsubject.'.SUB_CODE')
            ->select($tsubject.'.SUB_CODE', $tsubject.'.SUB_NAME', $tsubject.'.SUB_CREDIT', $tsubject.'.SUB_TYPE', $tgrade.'.GRADE' , $tgrade.'.SEMESTRY')
            ->orderByDesc($tgrade.'.SEMESTRY')
            ->get();

            $activity_data = DB::table($tactivity)
            ->where('STD_CODE', '=', $std_code)
            ->get();

            $sum_grade = DB::table($tgrade)
            ->where('STD_CODE', '=', $std_code)
            ->where('GRADE', '>', 0)
            ->join($tsubject, $tgrade.'.SUB_CODE', '=', $tsubject.'.SUB_CODE')
            ->select($tsubject.'.SUB_CODE', $tsubject.'.SUB_NAME', $tsubject.'.SUB_CREDIT', $tsubject.'.SUB_TYPE', $tgrade.'.GRADE')
            ->sum($tsubject.'.SUB_CREDIT');

            $sum_act = $activity_data->sum('HOUR');
            //echo $student_data, $grade_data, $activity_data;
            
            return view('teachers.tstudentprofile' ,compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act'));
        } else {
            return view('teachers.tstudentprofile' ,compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act'));
        }
    }
}
