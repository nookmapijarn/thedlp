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
        $grade_null = 0;
        $all_subject = [];
        $all_semestry = $this->get_semestry();
        $semestry = $all_semestry->first()->SEMESTRY;
        $all_tumbon = $this->get_group($semestry);

        if($request->tumbon!=''){
            $tumbon = str_split($request->tumbon, 4)[0];
            $lavel = $request->lavel;
            $semestry = $request->semestry;
            $subject = $request->subject;
            $type = $request->type;
            $all_subject = DB::table('subject'.$lavel)->select('SUB_CODE', 'SUB_NAME')->orderBy('SUB_CODE', 'ASC')->get();
        }else{
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not', 'grade_2_up', 'grade_null'));
        }

        $data = $this->grade_explore($tumbon, $lavel, $semestry, $subject, $type);
        
        if($data){
            $all_grade = $data->count();
            $grade_null = $data->where('GRADE', null)->count();
            $grade_not = $data->where('GRADE', 'LIKE', 'ข')->count();
            $grade_0 = $data->where('GRADE', '=', '0')->count();
            $grade_2_up = $data->where('GRADE', '>=', 2)->count();
            //print_r($resultsFiltered);
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not', 'grade_2_up', 'grade_null'));    
        } else {
            return view('teachers.tscore' ,compact('data', 'semestry', 'all_tumbon', 'all_semestry','all_subject', 'tumbon', 'lavel', 'subject', 'type', 'all_grade', 'grade_0', 'grade_not', 'grade_2_up', 'grade_null'));
        }
                 
    }

    public function grade_explore($grp_code, $lavel, $semestry, $subject, $type){
        $tgrade = 'grade'.$lavel;
        $tstudent = 'student'.$lavel;

        $student = DB::table($tgrade)
        ->join($tstudent, $tgrade.'.STD_CODE', '=', $tstudent.'.STD_CODE')
        ->where($tgrade.'.GRP_CODE', '=', $grp_code)
        ->where($tgrade.'.SEMESTRY', '=', $semestry)
        ->where($tgrade.'.SUB_CODE', '=', $subject)
        ->select(   $tstudent.'.STD_CODE', 
                    $tstudent.'.ID', 
                    $tstudent.'.PRENAME',
                    $tstudent.'.NAME', 
                    $tstudent.'.SURNAME', 
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
        ->orderBy($tstudent.'.STD_CODE', 'ASC')
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
    public function get_group($semestry)
    {
        // Implement the function to get all groups
        $Group = collect(); // ใช้ collection เพื่อเก็บผลลัพธ์
    
        for ($i = 1; $i <= 3; $i++) {
            $results = DB::table("grade{$i}")
                ->where("grade{$i}.SEMESTRY", $semestry)
                ->join("group", "group.GRP_CODE", '=', "grade{$i}.GRP_CODE")
                ->select("group.GRP_CODE", "group.GRP_NAME", "group.GRP_ADVIS")
                ->groupBy("group.GRP_CODE", "group.GRP_NAME", "group.GRP_ADVIS")
                ->orderBy("GRP_CODE", "ASC")
                ->get();
    
            $Group = $Group->merge($results)->unique()->sort(); // รวมผลลัพธ์
        }
        return $Group;
    }
}
