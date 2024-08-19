<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatareviweController extends Controller
{
    public function index(Request $request){
       
        ini_set('memory_limit', '10000M');

        if($request->table){

            $table = $request->table;

            // Retrieve the column names
            $columns = Schema::getColumnListing($table);
            
            // Retrieve the data from the table
            $data = DB::table($table)->get();

            return view('admin.datareview', compact('data', 'columns'));
            
        }else{

            $table = 'users';

            // Retrieve the column names
            $columns = Schema::getColumnListing($table);
            
            // Retrieve the data from the table
            $data = DB::table($table)->get();

            return view('admin.datareview', compact('data', 'columns'));
        }
    }
}
