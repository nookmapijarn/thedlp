<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use App\Models\Student1;
use App\Models\Student2;
use App\Models\Student3;
use App\Models\Group;
class TeachersController extends Controller
{
    public function index(Request $request)
    {   
        ini_set('max_execution_time', '256M');

        $agent = new Agent();  
        $limit = $agent->isMobile() ? 4 : 6; 
        $labels = $this->get_semestry($limit);
        $labels = array_reverse($labels);

        $current_semestry = $this->get_semestry(1)[0];
        
        $data_student = $this->get_student_counts($labels);
        // print_r($data_student['data_student']);

        // $province = $this->province();
        $all_tumbon = $this->get_group($current_semestry);
        $student_tumbon = $this->get_student_tumbon_counts($current_semestry, $all_tumbon); //จำนวนรายตำบล

        return view('teachers.tdashboard', compact('labels', 'data_student', 'all_tumbon', 'student_tumbon', 'current_semestry'));
    }

    public function get_student_counts($labels)
    {
        $data_student = [];
        $data_new_student = [];
        $data_old_student = [];
        
        foreach($labels as $val) {
            $allstudent = $this->current_student_count($val);
            $new_student = $this->new_student_count($val);

            $data_student[] = $allstudent;
            $data_new_student[] = $new_student;
            $data_old_student[] = $allstudent - $new_student;
        }

        return [
            'data_student' => $data_student,
            'data_new_student' => $data_new_student,
            'data_old_student' => $data_old_student
        ];
    }

    public function current_student_count($semestry)
    {
        $count = 0;
        for ($i = 1; $i <= 3; $i++) {
            $count += DB::table("grade{$i}")
                ->where("grade{$i}.SEMESTRY", $semestry)
                ->distinct("grade{$i}.STD_CODE")
                ->count("grade{$i}.STD_CODE");
        }
        return $count;
    }
    

    public function new_student_count($semestry)
    {
        $ID = str_replace('/', '', $semestry);
        $count = 0;
        for ($i = 1; $i <= 3; $i++) {
            $count += DB::table("student{$i}")
                ->whereRaw("ID REGEXP '^{$ID}[0-9]'")
                ->count("student{$i}.ID");
        }
    
        return $count;
    }

    public function get_student_tumbon_counts($semestry, $all_tumbon)
    {
        $student_tumbon = [];
    
        foreach ($all_tumbon as $tb) {
            // สืบค้นนักเรียนสำหรับทุกเกรดพร้อมกันโดยใช้ union
            $student_count = [];
            for ($i = 1; $i <= 3; $i++) {
                $count_s = DB::table("grade{$i}")
                    ->select('GRP_CODE', DB::raw("COUNT(DISTINCT STD_CODE) as student_count"))
                    ->where('SEMESTRY', $semestry)
                    ->where('GRP_CODE', $tb->GRP_CODE)
                    ->groupBy('GRP_CODE', 'SEMESTRY')
                    ->first();
    
                // เก็บข้อมูลจำนวนนักเรียนลงใน array
                $student_count["ST{$i}"] = $count_s ? $count_s->student_count : 0;
            }
    
            // เก็บข้อมูลของแต่ละ GRP
            $student_tumbon[] = [
                'GRP' => $tb,
                'STUDENT' => $student_count
            ];
        }
        echo json_encode($student_tumbon);
        return $student_tumbon;
    }
    

    public function get_semestry($limit)
    {
        return DB::table('grade3')
            ->select('SEMESTRY')
            ->orderBy('SEMESTRY', 'DESC')
            ->groupBy('SEMESTRY')
            ->take($limit)
            ->pluck('SEMESTRY')
            ->toArray();
    }

    public function province()
    {
        $current_semestry = $this->get_semestry(1)[0];
        return [
            ["hc-key" => "th-sh", "name" => 'สพรรณบุรี', "value" => Student1::where('ZIPCODE', '>=', 72000)->where('ZIPCODE', '<', 73000)->count()],
            ["hc-key" => "th-at", "name" => 'อ่างทอง', "value" => Student1::where('ZIPCODE', '>=', 14000)->where('ZIPCODE', '<', 15000)->count()],
            // Add more provinces as needed
        ];
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
        return $Group;
    }
    
}
