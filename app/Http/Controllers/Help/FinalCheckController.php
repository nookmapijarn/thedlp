<?php

namespace App\Http\Controllers\Help;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinalCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $value=[];

    public function index(){
        $value=$this->value;
        $old='';
        return view('finalcheck', compact('value', 'old'));
    }

    public function search(Request $request)
    {
        $old = $request->search;
        if(is_numeric($request->search)!=true){
            $value = DB::table('student')
                        ->where('NAME', 'like', '%'.$request->search.'%')
                        ->select('ID', 'NAME', 'SURNAME', 'FIN_CAUSE', 'FIN_SEM', 'TRNRUN')
                        ->get();
            $value = $this->formatdata($value);
                        return view('finalcheck', compact('value', 'old'));
        }else{
            $value = DB::table('student')
                        ->where('CARDID', '=', $request->search)
                        ->select('ID', 'NAME', 'SURNAME', 'FIN_CAUSE', 'FIN_SEM', 'TRNRUN')
                        ->get();
            $value = $this->formatdata($value);
                        return view('finalcheck', compact('value', 'old'));
        }
    }

    public function formatdata($data){
        $value = [];
        foreach ($data as $d){
            $obj = new \stdClass();
            $obj->ID = $d->ID;
            $obj->NAME = $d->NAME;
            $obj->SURNAME = $d->SURNAME;
            $obj->FIN_CAUSE = $d->FIN_CAUSE;
            $obj->FIN_SEM = $d->FIN_SEM;
            $obj->TRNRUN = $d->TRNRUN;

            $idsplit = str_split($d->ID, 1);
            $obj->LAVEL = $idsplit[3];

            array_push($value, $obj);
        }
        // print_r($value);
        return $value;
    }
}
