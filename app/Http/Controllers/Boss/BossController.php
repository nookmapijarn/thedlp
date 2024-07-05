<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;


class BossController extends Controller
{
    public function index(Request $request)
    {
        $all_semestry = DB::table('grade3')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $semestry = $all_semestry->first()->SEMESTRY;

        // SEMESTRY Labels
       $agent = new Agent();
       if( $agent->isMobile()){
        $labels = $this->get_semestry(3);
       }else{
        $labels = $this->get_semestry(6);
       }   
        
        $data_student = [];
        $data_new_student = [];
        $data_new_student_rollback = [];
        $data_finish_student = [];
        $data_studentPrimary = [];
        $data_studentJunior  = [];
        $data_studentSenior  = [];
        $data_exam_avg = [];
        $data_exam_avg_tumbon = [];
        $data_exam_avg_pangpub = [];
        $data_exam_avg_angkaew = [];
        $data_exam_avg_nongmeakai = [];
        $data_exam_avg_yangchay = [];
        $data_exam_avg_phorangnok = [];
        $data_exam_avg_rammasak = [];
        $data_exam_avg_bangrakum = [];
        $data_exam_avg_borei = [];
        $data_exam_avg_samngam = [];
        $data_exam_avg_thangpha = [];
        $data_exam_avg_inthapamoon = [];
        $data_exam_avg_aogkaruk = [];
        $data_exam_avg_kokpudsar = [];
        $data_exam_avg_bangjoacha = [];
        $data_exam_avg_kumyard = [];
        $data_exam_avg_pikan = [];
        $group = $this->get_group($semestry);

        // echo $this->finish_student('65/2')->Count()."SEMMMMMMM";

        $index = 0;

        $labels = array_reverse($labels, false);

        foreach($labels as $key=>$val) {
            // Use $key as an index, or...
            $allstudent = $this->current_student($val)->Count();
            $studentPrimary = $this->student_primary($val, '1')->Count();
            $studentJunior = $this->student_primary($val, '2')->Count();
            $studentSenior = $this->student_primary($val, '3')->Count();
            $new_student = $this->new_student($val)->Count();
            $new_student_rollback = $this->new_student_rollback($val)->Count();
            $finish_student = $this->finish_student($val)->Count();
            $exam_avg = $this->exam_avg($val)['result'];
            $exam_avg_pangpub = $this->exam_avg($val, '4011')['result'];
            $exam_avg_angkaew = $this->exam_avg($val, '4021')['result'];
            $exam_avg_nongmeakai = $this->exam_avg($val, '4031')['result'];
            $exam_avg_yangchay = $this->exam_avg($val, '4041')['result'];
            $exam_avg_phorangnok = $this->exam_avg($val, '4051')['result'];
            $exam_avg_rammasak = $this->exam_avg($val, '4061')['result'];
            $exam_avg_bangrakum = $this->exam_avg($val, '4071')['result'];
            $exam_avg_borei = $this->exam_avg($val, '4081')['result'];
            $exam_avg_samngam = $this->exam_avg($val, '4091')['result'];
            $exam_avg_thangpha = $this->exam_avg($val, '4101')['result'];
            $exam_avg_inthapamoon = $this->exam_avg($val, '4111')['result'];
            $exam_avg_aogkaruk = $this->exam_avg($val, '4121')['result'];
            $exam_avg_kokpudsar = $this->exam_avg($val, '4131')['result'];
            $exam_avg_bangjoacha = $this->exam_avg($val, '4141')['result'];
            $exam_avg_kumyard = $this->exam_avg($val, '4151')['result'];
            $exam_avg_pikan = $this->exam_avg($val, '4171')['result'];


            array_push($data_student, $allstudent);
            array_push($data_studentPrimary, $studentPrimary);
            array_push($data_studentJunior, $studentJunior);
            array_push($data_studentSenior, $studentSenior);
            array_push($data_new_student, $new_student);
            array_push($data_new_student_rollback, $new_student_rollback);
            array_push($data_finish_student, $finish_student);
            array_push($data_exam_avg, $exam_avg);
            // array_push($data_exam_avg_pangpub, $exam_avg_pangpub);
            // array_push($data_exam_avg_angkaew, $exam_avg_angkaew);
            // array_push($data_exam_avg_nongmeakai, $exam_avg_nongmeakai);
            // array_push($data_exam_avg_yangchay, $exam_avg_yangchay);
            // array_push($data_exam_avg_phorangnok, $exam_avg_phorangnok);
            // array_push($data_exam_avg_rammasak, $exam_avg_rammasak);
            // array_push($data_exam_avg_bangrakum, $exam_avg_bangrakum);
            // array_push($data_exam_avg_borei, $exam_avg_borei);
            // array_push($data_exam_avg_samngam, $exam_avg_samngam);
            // array_push($data_exam_avg_thangpha, $exam_avg_thangpha);
            // array_push($data_exam_avg_inthapamoon, $exam_avg_inthapamoon);
            // array_push($data_exam_avg_aogkaruk, $exam_avg_aogkaruk);
            // array_push($data_exam_avg_kokpudsar, $exam_avg_kokpudsar);
            // array_push($data_exam_avg_bangjoacha, $exam_avg_bangjoacha);
            // array_push($data_exam_avg_kumyard, $exam_avg_kumyard);
            // array_push($data_exam_avg_pikan, $exam_avg_pikan);
            // ... manage the index this way..
            //echo "Index is $index\n ".' Value ='.$val;
            $index++;
        }


        $allstudent = $this->current_student($semestry)->Count();

        $exam_avg = $this->exam_avg($semestry)['result'];
        $exam_avg_semestry = $this->exam_avg($semestry)['semestry'];

        $new_student = $this->new_student($semestry)->Count();
        $expectfin_student = 0;//$this->expectfin_student()->Count();
        $nofinish_student = 0;//$this->nofinish_student()->Count();
        
        echo 'EX : '.json_encode($data_exam_avg);

        return view('boss.bdashboard',compact('allstudent', 
                                                'exam_avg',   
                                                'semestry', 
                                                'exam_avg_semestry', 
                                                'new_student',
                                                'expectfin_student',
                                                'nofinish_student',
                                                'labels', 
                                                'data_student',
                                                'data_studentPrimary',
                                                'data_studentJunior',
                                                'data_studentSenior',
                                                'data_new_student',
                                                'data_new_student_rollback',
                                                'data_finish_student',
                                                'data_exam_avg',
                                                'data_exam_avg_pangpub',
                                                'data_exam_avg_angkaew',
                                                'data_exam_avg_nongmeakai',
                                                'data_exam_avg_yangchay',
                                                'data_exam_avg_phorangnok',
                                                'data_exam_avg_rammasak',
                                                'data_exam_avg_bangrakum',
                                                'data_exam_avg_borei',
                                                'data_exam_avg_samngam',
                                                'data_exam_avg_thangpha',
                                                'data_exam_avg_inthapamoon',
                                                'data_exam_avg_aogkaruk',
                                                'data_exam_avg_kokpudsar',
                                                'data_exam_avg_bangjoacha',
                                                'data_exam_avg_kumyard',
                                                'data_exam_avg_pikan'
                                            ));
    }

    public function current_student($semestry=''){
        $g1 = DB::table('grade1')
            ->select('STD_CODE')->where('SEMESTRY', $semestry)
            ->groupBy('STD_CODE');

        $g1_2 = DB::table('grade2')
            ->select('STD_CODE')
            ->where('SEMESTRY', $semestry)
            ->groupBy('STD_CODE')
            ->unionAll($g1);

        $gAll = DB::table('grade3')
            ->select('STD_CODE')
            ->where('SEMESTRY', $semestry)
            ->orderBy('STD_CODE', 'ASC')
            ->groupBy('STD_CODE')
            ->unionAll($g1_2)
            ->get();

        $g = $gAll;

        return $g;
    }
    public function get_semestry($limit)
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
            ->take($limit)
            ->pluck('SEMESTRY')
            ->toArray();
        // echo '<pre>';
        // echo print_r($semestry3);
        // echo '</pre>';
        return $semestry3;
    }

    public function get_semestry1($limit)
    {
        return DB::table('grade3')
            ->select('SEMESTRY')
            ->orderBy('SEMESTRY', 'ASC')
            ->groupBy('SEMESTRY')
            ->take($limit)
            ->pluck('SEMESTRY')
            ->toArray();
    }

    public function student_primary($semestry, $tlavel){
        //$ID = str_replace('/','',$semestry);
        $s = DB::table('grade'.$tlavel)
            ->where('SEMESTRY', $semestry)
            ->select('STD_CODE')
            ->groupBy('STD_CODE')
            ->get();
        return $s;
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

    public function new_student($semestry){
        $ID = str_replace('/','',$semestry); // เช่น '661'
    
        $student1 = DB::table('student1')
            ->select('ID')
            ->whereRaw("ID REGEXP '^{$ID}[0-9]'")
            ->groupBy('ID');
    
        $student1_2 = DB::table('student2')
            ->select('ID')
            ->whereRaw("ID REGEXP '^{$ID}[0-9]'")
            ->groupBy('ID')
            ->unionAll($student1);
    
        $studentAll = DB::table('student3')
            ->select('ID')
            ->whereRaw("ID REGEXP '^{$ID}[0-9]'")
            ->groupBy('ID')
            ->unionAll($student1_2)
            ->orderBy('ID', 'ASC')
            ->get();
    
        return $studentAll;
    }
    

    public function new_student_rollback($semestry){
        $ID = str_replace('/','',$semestry); // ตัวอย่างเช่น '661'
        $ID = str_split($ID, 2); // ตัดเป็น array เช่น ['66', '1']
        $year = $ID[0] - 2; // ปีที่ต้องการ rollback
        $semes = ($ID[1] === '1' ? '2' : '1'); // เทอมที่ต้องการ rollback
        $rollback_ID = $year.$semes; // ID ที่จะใช้ในการ rollback
    
        // สร้าง query ด้วยการใช้ whereRaw() เพื่อใช้งาน regexp ใน MySQL
        $student1 = DB::table('student1')
            ->whereRaw("ID REGEXP '^{$rollback_ID}[0-9]'")
            ->select('ID', DB::raw("'student1' as source"));
    
        $student1_2 = DB::table('student2')
            ->whereRaw("ID REGEXP '^{$rollback_ID}[0-9]'")
            ->select('ID', DB::raw("'student2' as source"))
            ->unionAll($student1);
    
        $studentAll = DB::table('student3')
            ->whereRaw("ID REGEXP '^{$rollback_ID}[0-9]'")
            ->select('ID', DB::raw("'student3' as source"))
            ->unionAll($student1_2);
    
        $s = $studentAll
            ->orderBy('ID', 'ASC')
            ->groupBy('ID')
            ->get();
    
        return $s;
    }
    

    public function finish_student($semestry){
        $student1 = DB::table('student1')
            ->where('FIN_CAUSE', '=', 1)
            ->where('FIN_SEM', $semestry)
            ->select('STD_CODE', DB::raw("'student1' as source"));
    
        $student1_2 = DB::table('student2')
            ->where('FIN_CAUSE', '=', 1)
            ->where('FIN_SEM', $semestry)
            ->select('STD_CODE', DB::raw("'student2' as source"))
            ->unionAll($student1);
    
        $studentAll = DB::table('student3')
            ->where('FIN_CAUSE', '=', 1)
            ->where('FIN_SEM', $semestry)
            ->select('STD_CODE', DB::raw("'student3' as source"))
            ->unionAll($student1_2);
        
        $s = $studentAll
            ->orderBy('STD_CODE', 'ASC')
            ->groupBy('STD_CODE')
            ->get();
        
        return $s;
    }
    

    // public function expectfin_student(){
    //     $expectfin = DB::table('expectfin')
    //     ->select('ID')
    //     ->orderBy('ID', 'ASC')
    //     ->groupBy('ID')
    //     ->get();
    //     return $expectfin;
    // }

    // public function nofinish_student(){
    //     $nofinish_student = DB::table('expectfin1')
    //     ->select('ID')
    //     ->orderBy('ID', 'ASC')
    //     ->groupBy('ID')
    //     ->get();
    //     return $nofinish_student;
    // }
    public function exam_avg($semestry, $tumbon=''){

        // if($tumbon!=''){

        //     $grade1 = DB::table('grade1')
        //         ->where('SEMESTRY', $semestry)
        //         ->where('GRP_CODE', $tumbon)
        //         ->select('STD_CODE');
            
        //     $grade1_2 = DB::table('grade2')
        //         ->where('SEMESTRY', $semestry)
        //         ->where('GRP_CODE', $tumbon)
        //         ->select('STD_CODE')
        //         ->unionAll($grade1);
            
        //     $exam_grade = DB::table('grade3')
        //         ->where('SEMESTRY', $semestry)
        //         ->where('GRP_CODE', $tumbon)
        //         ->where('GRADE', '!=', 'ข')
        //         ->where('GRADE', '!=', '')
        //         ->select('STD_CODE')
        //         ->groupBy('STD_CODE')
        //         ->unionAll($grade1_2)
        //         ->get();
            
        //     $all_grade = DB::table('grade3')
        //         ->where('SEMESTRY', $semestry)
        //         ->where('GRP_CODE', $tumbon)
        //         ->select('STD_CODE')
        //         ->groupBy('STD_CODE')
        //         ->unionAll($grade1_2)
        //         ->get();
        // } else {
            $grade1 = DB::table('grade1')
                ->where('SEMESTRY', $semestry)
                ->where('GRADE', 'NOT REGEXP', '[ข]')
                ->where('GRADE', '!=', null)
                ->select('STD_CODE', 'GRADE');
            
            $grade1_2 = DB::table('grade2')
                ->where('SEMESTRY', $semestry)
                ->where('GRADE', 'NOT REGEXP', '[ข]')
                ->where('GRADE', '!=', null)
                ->select('STD_CODE', 'GRADE')
                ->unionAll($grade1);
            
            $exam_grade = DB::table('grade3')
                ->where('SEMESTRY', $semestry)
                ->unionAll($grade1_2)
                ->where('GRADE', 'NOT REGEXP', '[ข]')
                ->where('GRADE', '!=', null)
                ->select('STD_CODE', 'GRADE')
                // ->groupBy('STD_CODE', 'GRADE')
                ->get();
        
            $all_grade = DB::table('grade3')
                ->where('SEMESTRY', $semestry)
                ->select('STD_CODE', 'GRADE')
                // ->groupBy('STD_CODE', 'GRADE')
                ->unionAll($grade1_2)
                ->get();
        // }

        // echo 'All ->'.$all_grade->Count().'<br>';
        // echo 'g ->'.$exam_grade->Count().'<br>';

        if($exam_grade->Count()!=0){

            $result = ($exam_grade->Count()*100)/$all_grade->Count();

            return [
                'result'   => round($result, 2), 
                'semestry' => $semestry
            ];
        }else{
            //echo $exam_grade;
            return [
                'result'   => 0, 
                'semestry' => $semestry
            ];
        }
         
    }
}
