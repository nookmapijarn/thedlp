<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Echo_;

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
        $all_semestry = $this->get_semestry();
        
        $data=[];
        $tumbon = '';
        $lavel = '';
        $semestry = $all_semestry->first()->SEMESTRY;

        $all_tumbon = $this->get_group($semestry);

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
        
        $tgrade = "grade{$lavel}";
        $tstudent = "student{$lavel}";

        $student = DB::table($tgrade)
        ->join($tstudent, $tgrade.'.STD_CODE', '=', $tstudent.'.STD_CODE')
        ->where($tgrade.'.GRP_CODE', '=', $grp_code)
        ->where($tgrade.'.SEMESTRY', '=', $semestry)
        ->select($tstudent.'.STD_CODE', $tstudent.'.ID', $tstudent.'.NAME', $tstudent.'.SURNAME')
        ->orderBy($tstudent.'.ID', 'ASC')
        ->groupBy($tstudent.'.STD_CODE', $tstudent.'.ID', $tstudent.'.NAME', $tstudent.'.SURNAME')
        ->get();
        //echo $student;
        return $student;
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
                ->get();
    
            $Group = $Group->merge($results); // รวมผลลัพธ์
        }
    
        // echo "<pre>";
        // print_r($Group->toArray()); // แปลงเป็น array ก่อนแสดงผล
        // echo "</pre>";        
        // echo '<br><br><br><br>****************************************************************' . $Group->count();
    
        return $Group;
    }
}
