<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use App\Providers\CustomServiceProvider;


class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct(CustomServiceProvider $customService)
    // {
    //     $customService->setSemestry(66/2);
    // }
    protected $semestry = '67/1';

    public function index(Request $request)
    {   
        $labels = [];
        $data_student = [];
        $data_new_student = [];

       // SEMESTRY Labels
       $agent = new Agent();
       if( $agent->isMobile()){
        $labels = $this->get_semestry(6);
       }else{
        $labels = $this->get_semestry(12);
       }   

       $index = 0;
       foreach($labels as $key=>$val) {
        // จำนวนนักศึกษาทั้งหมด
        $allstudent = $this->current_student($val)->Count();
        array_push($data_student, $allstudent);
        // จำนวนนักศึกษาใหม่
        $new_student = $this->new_student($val)->Count();
        array_push($data_new_student, $new_student);
        $index++;   
        }

        return view('teachers.tdashboard', compact('labels', 'data_student', 'data_new_student'));
    }

    public function current_student($semestry=''){
        $g = DB::table('grade')
        ->where('SEMESTRY', $semestry)
        ->select('STD_CODE')
        ->orderBy('STD_CODE', 'ASC')
        ->groupBy('STD_CODE')
        ->get();
        return $g;
    }

    public function get_semestry($limit){
        $sem = DB::table('grade')
        ->select('SEMESTRY')
        ->orderBy('SEMESTRY', 'DESC')
        ->groupBy('SEMESTRY')
        ->take($limit)
        ->get();

        $isem = [];

        foreach($sem as $s){
            array_push($isem, $s->SEMESTRY);
        }
        return  array_reverse($isem);
    }
    
    public function new_student($semestry){
        $ID = str_replace('/','',$semestry); //661
        $s = DB::table('student')
        ->where('ID', 'regexp', $ID.'[0-9]')
        ->select('ID')
        ->orderBy('ID', 'ASC')
        ->groupBy('ID')
        ->get();
        return $s;
    }
}
