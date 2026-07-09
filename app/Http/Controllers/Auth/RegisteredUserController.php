<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. First-pass validation to ensure student_id and citizen_id are formatted correctly
        $request->validate([
            'student_id' => ['required', 'string', 'regex:/^\d{10}$/'],
            'citizen_id' => ['required', 'string', 'regex:/^\d{13}$/'],
        ], [
            'student_id.required' => 'โปรดระบุรหัสนักศึกษา 10 หลัก',
            'student_id.regex' => 'รหัสนักศึกษาต้องเป็นตัวเลข 10 หลัก',
            'citizen_id.required' => 'โปรดระบุเลขประจำตัวประชาชน 13 หลัก',
            'citizen_id.regex' => 'เลขประจำตัวประชาชนต้องเป็นตัวเลข 13 หลัก',
        ]);

        $id = $request->student_id;
        $citizenId = $request->citizen_id;
        $lavel = str_split($id, 1)[3]; // ดึงหลักที่ 4 ของรหัส เพื่อเช็คระดับชั้น (1, 2, 3)

        // ตรวจสอบระดับชั้น
        if (!in_array($lavel, ['1', '2', '3'])) {
            return back()->withInput()->withErrors(['student_id' => 'รหัสนักศึกษาไม่ถูกต้อง (ระบุระดับชั้นไม่ได้)']);
        }

        // 2. Validate student ID registry matching and other fields
        $request->validate([
            'student_id' => ['unique:'.User::class],
            'citizen_id' => [
                function ($attribute, $value, $fail) use ($id, $lavel) {
                    $student = DB::table("student{$lavel}")->where('ID', $id)->first();
                    if (!$student) {
                        $fail('ไม่พบข้อมูลรหัสนักศึกษานี้ในระบบทะเบียน');
                    } else {
                        // Normalize keys to lowercase to prevent database casing discrepancy on Linux
                        $studentNorm = array_change_key_case((array)$student, CASE_LOWER);
                        $dbCard = trim($studentNorm['cardid'] ?? '');
                        $inputCard = trim($value);
                        if ($dbCard !== $inputCard) {
                            $fail('เลขประจำตัวประชาชนไม่ถูกต้อง หรือไม่ตรงกับรหัสนักศึกษาในระบบ');
                        }
                    }
                }
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'pdpa_check' => 'accepted',
        ], [
            'student_id.unique' => 'รหัสนักศึกษานี้ได้ทำการสมัครสมาชิกไปแล้ว',
            'name.required' => 'โปรดระบุชื่อผู้ใช้งาน',
            'email.required' => 'โปรดระบุอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้สมัครสมาชิกไปแล้ว',
            'password.required' => 'โปรดระบุรหัสผ่าน',
            'password.confirmed' => 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน',
            'pdpa_check.accepted' => 'โปรดกดยอมรับนโยบายคุ้มครองข้อมูลส่วนบุคคล (PDPA)'
        ]);

        $user = User::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pdpa_check' => $request->has('pdpa_check'),
            'role' => 1,
            'avatar' => null
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
