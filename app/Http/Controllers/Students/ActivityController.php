<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $lavel;
    protected $std_code;

    public function index(){

        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $this->std_code = DB::table("student{$this->lavel}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

        $activity = $this->get_activity();
        //print_r($notlearned);
        return view('students.activity' ,compact('activity'));
    }
    public function get_activity(){
        $activity = DB::table("activity{$this->lavel}")
        ->where('STD_CODE', $this->std_code)
        ->orderBy('SEMESTRY', 'ASC')
        ->get();
        //print_r($activity[1]);     
        return $activity;
    }
}
