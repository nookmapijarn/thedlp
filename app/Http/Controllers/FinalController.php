<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;

class FinalController extends Controller
{
    //
    public function Index(){
        return view('final');
    }
    
}
