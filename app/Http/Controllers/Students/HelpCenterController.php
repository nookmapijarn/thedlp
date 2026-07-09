<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\HelpRequestLog;
use App\Models\HelpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HelpCenterController extends Controller
{
    public function index()
    {
        $id = auth()->user()->student_id;
        $lavel = str_split($id, 1)[3];
        $student = DB::table("student{$lavel}")->where('ID', $id)->first();

        // ดึงประวัติการแจ้งขอความช่วยเหลือของผู้เรียนคนนี้ พร้อมข้อมูล logs การบันทึกการดำเนินการ
        $helpRequests = HelpRequest::with(['logs.user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('students.help', compact('student', 'helpRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'category.required' => 'กรุณาเลือกหมวดหมู่ปัญหา',
            'subject.required' => 'กรุณาระบุหัวข้อปัญหา',
            'message.required' => 'กรุณากรอกรายละเอียดปัญหา',
        ]);

        // Create help request
        $helpRequest = HelpRequest::create([
            'user_id' => auth()->id(),
            'student_id' => auth()->user()->student_id,
            'subject' => $request->subject,
            'category' => $request->category,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // 1. Create first step log (บันทึกการส่งเรื่อง)
        HelpRequestLog::create([
            'help_request_id' => $helpRequest->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'action_detail' => 'ยื่นคำขอความช่วยเหลือ',
            'note' => 'ผู้เรียนได้สร้างคำร้องขอความช่วยเหลือและส่งเข้าระบบเรียบร้อยแล้ว',
        ]);

        // 2. Notify all Teachers (role 2) and Admins (role 4)
        $msg = "มีคำร้องขอความช่วยเหลือใหม่: \"" . $request->subject . "\" จากผู้เรียน " . auth()->user()->name;
        HelpNotification::notifyStaff($helpRequest, $msg);

        return redirect()->route('help.index')->with('success', 'บันทึกข้อมูลและส่งเรื่องขอความช่วยเหลือสำเร็จเรียบร้อยแล้ว');
    }
}
