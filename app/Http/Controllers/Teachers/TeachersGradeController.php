<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TeachersGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */  

    public function index(Request $request)
    {
        //
        $id = auth()->user()->student_id;
        $all_tumbon = DB::table('group')->select('GRP_CODE', 'GRP_NAME')->orderBy('GRP_CODE', 'ASC')->get();
        $all_semestry = DB::table('grade')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        
        $data=[];
        $tumbon = '';
        $lavel = '';
        $semestry = $all_semestry->first()->SEMESTRY;


        if ($id != '1215040001') {
            return redirect('welcome/?roletype='.$id);
        }

        if($request->tumbon!=''){
            $tumbon = str_split($request->tumbon, 4)[0];
            $lavel = $request->lavel;
            $semestry = $request->semestry;
        }else{
            return view('teachers.tgrade' ,compact('data', 'semestry', 'tumbon', 'lavel', 'all_tumbon', 'all_semestry'));
        }

        $data = $this->grade_explore($tumbon, $lavel, $semestry);
        //$this->current_student($tumbon, $lavel);

        return view('teachers.tgrade' ,compact('data', 'semestry', 'tumbon', 'lavel', 'all_tumbon', 'all_semestry'));                     
        //return view('teachers.tdashboard' ,compact('data'));
    }

    public function grade_explore($grp_code, $lavel, $semestry){
        $student = $this->current_student($grp_code, $lavel, $semestry);
        $tgrade = 'grade'.$lavel;
        $sgrade = [];

        foreach ($student as $s) {
            $grade = DB::table($tgrade)
            ->where('GRP_CODE', '=', $grp_code)
            ->where('STD_CODE', '=', $s->STD_CODE)
            ->where('SEMESTRY', '=', $semestry)
            ->select('SUB_CODE', 'FINAL', 'TOTAL',  'GRADE', 'TYP_CODE')
            ->orderBy('SUB_CODE', 'ASC')
            ->get();

            array_push($sgrade, 
                [
                    'ID'        =>  $s->ID,
                    'NAME'      =>  $s->NAME,
                    'SURNAME'   =>  $s->SURNAME,
                    'ALL_GRADE' =>  $grade
                ]
            ); 
        }
        
        //echo json_encode($sgrade);
        return $sgrade;
    }

    public function current_student($grp_code, $lavel, $semestry){
        $tgrade = 'grade'.$lavel;
        $student = DB::table($tgrade)
        ->join('student', $tgrade.'.STD_CODE', '=', 'student.STD_CODE')
        ->where($tgrade.'.GRP_CODE', '=', $grp_code)
        ->where($tgrade.'.SEMESTRY', '=', $semestry)
        ->select('student.STD_CODE', 'student.ID', 'student.NAME', 'student.SURNAME')
        ->orderBy('student.ID', 'ASC')
        ->groupBy('student.STD_CODE', 'student.ID', 'student.NAME', 'student.SURNAME')
        ->get();
        //echo $student;
        return $student;
    }
}
