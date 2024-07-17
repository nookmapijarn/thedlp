<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AdminUserController extends Controller
{
    public function index(){
        $users = [];
        $users_admin = [];
        $users = DB::table('users')->orderBy('role', 'DESC')->get();

        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {

        $request->validate([
                'role' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'pdpa_check' => 'accepted',
            ], [
                'role' => 'ไม่มี role',
                // 'student_id.max' => 'รหัสต้องมี 10 หลัก',
                // 'student_id.in' => 'ไม่มีนักศึกษารหัสนี้',
                'email.email' => 'email ไม่ถูกต้อง',
                'email.max' => 'email ยาวเกินไป',
                'email.unique' => 'email ถูกใช้ไปแล้ว',
                'password.confirmed' => 'Password ไม่ตรงกัน',
                'pdpa_check.accepted' => 'โปรดกดยอมรับ'
            ]);

        
        $user = User::create([
            'student_id' => '0000000000',
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pdpa_check' => $request->has('pdpa_check'),
            'role' => $request->role
        ]);

        $users = DB::table('users')->orderBy('created_at', 'DESC')->get();

        return redirect()->route('adminregister')->with('success', 'เพิ่มข้อมูลสำเร็จ.');
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
    
        return response()->json(['success' => 'User updated successfully']);
    }
    
    public function remove(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'User removed successfully']);
        }
    
        return response()->json(['error' => 'User not found'], 404);
    }
}
