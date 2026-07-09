<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\HelpRequestLog;
use App\Models\HelpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminHelpRequestController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status', 'all');

        $query = HelpRequest::with(['user', 'logs.user'])->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $helpRequests = $query->paginate(20);

        return view('admin.help_requests.index', compact('helpRequests', 'statusFilter'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:2000',
            'status' => 'required|string|in:pending,in_progress,resolved,rejected',
            'action_note' => 'nullable|string|max:2000',
        ]);

        $helpRequest = HelpRequest::findOrFail($id);
        $oldStatus = $helpRequest->status;
        $newStatus = $request->status;

        $helpRequest->update([
            'admin_reply' => $request->admin_reply,
            'status' => $newStatus,
        ]);

        // Create log entry
        $actionDetail = "ผู้ดูแลระบบ ตอบกลับคำร้อง";
        if ($oldStatus !== $newStatus) {
            $actionDetail .= " และเปลี่ยนสถานะจาก " . $this->getStatusThai($oldStatus) . " เป็น " . $this->getStatusThai($newStatus);
        }

        HelpRequestLog::create([
            'help_request_id' => $helpRequest->id,
            'user_id' => auth()->id(),
            'status' => $newStatus,
            'action_detail' => $actionDetail,
            'note' => $request->action_note ?? $request->admin_reply,
        ]);

        // Send notification to Student
        $msg = "คำร้องขอความช่วยเหลือของคุณได้รับการตอบกลับโดยผู้ดูแลระบบ (สถานะ: " . $this->getStatusThai($newStatus) . ")";
        HelpNotification::sendNotification($helpRequest->user_id, $helpRequest->id, $msg);

        return redirect()->route('admin.help.index')->with('success', 'ตอบกลับความช่วยเหลือผู้เรียนเรียบร้อยแล้ว');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in_progress,resolved,rejected',
            'action_note' => 'nullable|string|max:2000',
        ]);

        $helpRequest = HelpRequest::findOrFail($id);
        $oldStatus = $helpRequest->status;
        $newStatus = $request->status;

        $helpRequest->update([
            'status' => $newStatus,
        ]);

        $actionDetail = "ผู้ดูแลระบบ อัปเดตสถานะเป็น " . $this->getStatusThai($newStatus);

        HelpRequestLog::create([
            'help_request_id' => $helpRequest->id,
            'user_id' => auth()->id(),
            'status' => $newStatus,
            'action_detail' => $actionDetail,
            'note' => $request->action_note ?? "ปรับเปลี่ยนสถานะการดำเนินงานเป็น " . $this->getStatusThai($newStatus),
        ]);

        // Send notification to Student
        $msg = "คำร้องขอความช่วยเหลือของคุณได้รับการเปลี่ยนสถานะเป็น " . $this->getStatusThai($newStatus);
        HelpNotification::sendNotification($helpRequest->user_id, $helpRequest->id, $msg);

        return redirect()->route('admin.help.index')->with('success', 'อัปเดตสถานะความช่วยเหลือเรียบร้อยแล้ว');
    }

    private function getStatusThai($status)
    {
        switch ($status) {
            case 'pending': return 'รอตรวจสอบ';
            case 'in_progress': return 'กำลังดำเนินการ';
            case 'resolved': return 'แก้ไขเสร็จสิ้น';
            case 'rejected': return 'ปฏิเสธคำขอ';
            default: return $status;
        }
    }
}
