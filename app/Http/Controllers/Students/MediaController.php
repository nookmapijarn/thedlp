<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MediaController extends Controller
{

    protected $lavel;
    protected $std_code;

    public function index(){

        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $this->std_code = DB::table("student{$this->lavel}")->where('ID', $id)->select('STD_CODE')->groupBy('STD_CODE')->value('STD_CODE');

        $all_semestry = DB::table("grade{$this->lavel}")->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        $semestry = $all_semestry->first()->SEMESTRY;
        
        return view('students.media');
    }

}
