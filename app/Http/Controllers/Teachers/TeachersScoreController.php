<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SplSubject;

class TeachersScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */  

    public function index(Request $request)
    {
        //
        $data=[];
        $tumbon = '';
        $lavel = '';
        $subject = '';
        $semestry = '';
        $type = '';
        $all_grade = 0;
        $grade_not = 0;
        $grade_0 = 0;
        $grade_2_up = 0;
        $all_tumbon = DB::table('group')->select('GRP_CODE', 'GRP_NAME')->orderBy('GRP_CODE', 'ASC')->get();
        $all_subject = DB::table('subject'.$lavel)->select('SUB_CODE', 'SUB_NAME')->orderBy('SUB_CODE', 'ASC')->get();
        $all_semestry = DB::table('grade')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();

        $id = auth()->user()->student_id;

        if ($id != '1215040001') {
            return redirect('welcome/?roletype='.$id);
        }

        if($request->tumbon!=''){
            $tumbon = str_split($request->tumbon, 4)[0];
            $lavel = $request->lavel;
            $semestry = $request->semestry;
            $subject = $request->subject;
            $type = $request->type;
            $all_subject = DB::table('subject'.$lavel)->select('SUB_CODE', 'SUB_NAME')->orderBy('SUB_CODE', 'ASC')->get();
        }else{
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not', 'grade_2_up'));
        }

        $data = $this->grade_explore($tumbon, $lavel, $semestry, $subject, $type);
        
        if($data){
            $all_grade = $data->count();
            $grade_not = $data->where('GRADE', 'LIKE', 'ข')->count();
            $grade_0 = $data->where('GRADE', '=', '0')->count();
            $grade_2_up = $data->where('GRADE', '>=', 2)->count();
            //print_r($resultsFiltered);
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not', 'grade_2_up'));    
        } else {
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not', 'grade_2_up'));
        }
                 
    }

    public function grade_explore($grp_code, $lavel, $semestry, $subject, $type){
        $tgrade = 'grade'.$lavel;
        $student = DB::table($tgrade)
        ->join('student', $tgrade.'.STD_CODE', '=', 'student.STD_CODE')
        ->where($tgrade.'.GRP_CODE', '=', $grp_code)
        ->where($tgrade.'.SEMESTRY', '=', $semestry)
        ->where($tgrade.'.SUB_CODE', '=', $subject)
        ->select(   'student.STD_CODE', 
                    'student.ID', 
                    'student.PRENAME',
                    'student.NAME', 
                    'student.SURNAME', 
                    $tgrade.'.SUB_CODE',
                    $tgrade.'.FINAL',   
                    $tgrade.'.TOTAL',
                    $tgrade.'.TYP_CODE',
                    $tgrade.'.GRADE',
                    $tgrade.'.MIDTERM',
                    $tgrade.'.MIDTERM1',
                    $tgrade.'.MIDTERM2',
                    $tgrade.'.MIDTERM3',
                    $tgrade.'.MIDTERM4',
                    $tgrade.'.MIDTERM5',
                    $tgrade.'.MIDTERM6',
                    $tgrade.'.MIDTERM7',
                    $tgrade.'.MIDTERM8',
                    $tgrade.'.MIDTERM9',
                )
        ->orderBy('student.STD_CODE', 'ASC')
        ->get();

        if ($student->isEmpty()) {
            return $student=null; // Return an empty array if no results found
          } else {
                    // กรองข้อมูล
                    if($type == 0){
                        $student = $student->reject(function ($record) {
                            return $record->TYP_CODE == 7;
                        });
                    } else if ($type == 7) {
                        $student = $student->where('TYP_CODE', '=', 7)->reject(function ($record) {
                            return $record->TYP_CODE != 7;
                        });
                    }
             return $student;
          }
    }
}
