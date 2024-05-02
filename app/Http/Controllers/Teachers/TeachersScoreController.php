<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SplSubject;

class TeachersScoreController extends Controller
{
    //
    protected $semestry = '66/2';

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
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not'));
        }

        $data = $this->grade_explore($tumbon, $lavel, $semestry, $subject, $type);
        
        if($data){
            $all_grade = $data->count();
            $grade_not = $data->where('GRADE', 'LIKE', 'ข')->count();
            $grade_0 = $data->where('GRADE', '=', '0')->count();
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not'));    
        } else {
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not'));
        }
                 
    }

    public function grade_explore($grp_code, $lavel, $semestry, $subject, $type){
        $tgrade = 'grade'.$lavel;
        $typ_where = $type == 7 ? "like": "not LIKE"; // 7 หมายถึง เกรดสอบซ่อม
        $student = DB::table($tgrade)
        ->join('student', $tgrade.'.STD_CODE', '=', 'student.STD_CODE')
        ->where($tgrade.'.GRP_CODE', '=', $grp_code)
        ->where($tgrade.'.SEMESTRY', '=', $semestry)
        ->where($tgrade.'.SUB_CODE', '=', $subject)
        ->where($tgrade.'.TYP_CODE', $typ_where, 7)
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
        ->orderBy($tgrade.'.SUB_CODE', 'ASC')
        //->groupBy('student.STD_CODE', 'student.ID', 'student.NAME', 'student.SURNAME')
        ->get();
        //echo $student;
        if ($student->isEmpty()) {
            return $student=null; // Return an empty array if no results found
          } else {
            return $student;
          }
    }
}