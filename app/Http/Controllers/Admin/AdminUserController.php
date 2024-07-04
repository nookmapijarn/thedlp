<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class AdminUserController extends Controller
{
    public function index(){
        $users = [];
        $users = DB::table('users')->orderBy('created_at', 'DESC')->get();

        return view('admin.users', compact('users'));
    }
}
