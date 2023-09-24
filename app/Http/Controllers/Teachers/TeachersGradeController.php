<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TeachersGradeController extends Controller
{
    protected $semestry = '66/1';
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
        $semestry = $this->semestry;
        $id = auth()->user()->student_id;

        if ($id != '1215040001') {
            return redirect('welcome/?roletype='.$id);
        }

        if($request->tumbon!=''){
            $tumbon = str_split($request->tumbon, 4)[0];
            $lavel = $request->lavel;
        }else{
            return view('teachers.tgrade' ,compact('data', 'semestry'));
        }

        $data = $this->grade_explore($tumbon, $lavel);
        //$this->current_student($tumbon, $lavel);

        return view('teachers.tgrade' ,compact('data', 'semestry'));                     
        //return view('teachers.tdashboard' ,compact('data'));
    }

    public function grade_explore($grp_code, $lavel){
        $student = $this->current_student($grp_code, $lavel);
        $tgrade = 'grade'.$lavel;
        $sgrade = [];
        foreach ($student as $s) {
            $grade = DB::table($tgrade)
            ->where('GRP_CODE', '=', $grp_code)
            ->where('STD_CODE', '=', $s->STD_CODE)
            ->where('SEMESTRY', '=', $this->semestry)
            ->select('SUB_CODE', 'FINAL', 'TOTAL',  'GRADE')
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

    public function current_student($grp_code, $lavel){
        $tgrade = 'grade'.$lavel;
        $student = DB::table($tgrade)
        ->join('student', $tgrade.'.STD_CODE', '=', 'student.STD_CODE')
        ->where($tgrade.'.GRP_CODE', '=', $grp_code)
        ->where($tgrade.'.SEMESTRY', '=', $this->semestry)
        ->select('student.STD_CODE', 'student.ID', 'student.NAME', 'student.SURNAME')
        ->orderBy('student.ID', 'ASC')
        ->groupBy('student.STD_CODE', 'student.ID', 'student.NAME', 'student.SURNAME')
        ->get();
        //echo $student;
        return $student;
    }
}
