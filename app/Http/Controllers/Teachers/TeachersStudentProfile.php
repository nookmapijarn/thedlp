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
            $student_id = $request->student_id;

            $student_data = DB::table('student')
            ->where('STD_CODE', '=', '1215040001'.$student_id)
            ->get();

            $grade_data = DB::table('grade')
            ->where('STD_CODE', '=', '1215040001'.$student_id)
            ->where('GRADE', '>', 0)
            ->join('subject', 'grade.SUB_CODE', '=', 'subject.SUB_CODE')
            ->select('subject.SUB_CODE', 'subject.SUB_NAME', 'subject.SUB_CREDIT', 'subject.SUB_TYPE', 'grade.GRADE' , 'grade.SEMESTRY')
            ->orderByDesc('grade.SEMESTRY')
            ->get();

            $activity_data = DB::table('activity')
            ->where('STD_CODE', '=', '1215040001'.$student_id)
            ->get();

            $sum_grade = DB::table('grade')
            ->where('STD_CODE', '=', '1215040001'.$student_id)
            ->where('GRADE', '>', 0)
            ->join('subject', 'grade.SUB_CODE', '=', 'subject.SUB_CODE')
            ->select('subject.SUB_CODE', 'subject.SUB_NAME', 'subject.SUB_CREDIT', 'subject.SUB_TYPE', 'grade.GRADE')
            ->sum('subject.SUB_CREDIT');

            $sum_act = DB::table('activity')
            ->where('STD_CODE', '=', '1215040001'.$student_id)
            ->sum('HOUR');
            //echo $student_data, $grade_data, $activity_data;
            
            return view('teachers.tstudentprofile' ,compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act'));
        } else {
            return view('teachers.tstudentprofile' ,compact('student_data', 'grade_data', 'activity_data', 'sum_grade', 'sum_act'));
        }
    }
}
