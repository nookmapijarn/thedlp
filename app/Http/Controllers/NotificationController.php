<?php

namespace App\Http\Controllers;

use App\Models\HelpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Mark notification as read and redirect user to the appropriate page.
     */
    public function read($id)
    {
        $notification = HelpNotification::findOrFail($id);

        // Security check
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $notification->update(['is_read' => true]);

        // Redirect based on user role and append ticket ID for auto-focus/expansion
        $role = auth()->user()->role;
        $ticketId = $notification->help_request_id;

        if ($role == 4) {
            return redirect()->route('admin.help.index', ['ticket' => $ticketId]);
        } elseif ($role == 2) {
            return redirect()->route('teachers.help.index', ['ticket' => $ticketId]);
        } else {
            return redirect()->route('help.index', ['ticket' => $ticketId]);
        }
    }

    /**
     * Mark all notifications as read for current user.
     */
    public function readAll()
    {
        HelpNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'อ่านการแจ้งเตือนทั้งหมดเรียบร้อยแล้ว');
    }

    /**
     * Retrieve chat messages (logs) for a specific help ticket.
     */
    public function getMessages($id)
    {
        $helpRequest = \App\Models\HelpRequest::with(['logs.user', 'user'])->findOrFail($id);

        // Security check
        $user = auth()->user();
        if ($user->role == 1 && $helpRequest->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = [];
        // First message is the original student ticket message
        $messages[] = [
            'id' => 'orig-' . $helpRequest->id,
            'user_id' => $helpRequest->user_id,
            'sender_name' => $helpRequest->user?->name ?? 'ผู้เรียน',
            'sender_role' => 1,
            'type' => 'message',
            'text' => $helpRequest->message,
            'time' => $helpRequest->created_at->addYears(543)->locale('th')->isoFormat('D MMM YY, HH:mm') . ' น.'
        ];

        foreach ($helpRequest->logs as $log) {
            $isSystem = false;
            if (Str::startsWith($log->action_detail, 'ยื่นคำขอ') || Str::contains($log->action_detail, 'อัปเดตสถานะ') || Str::contains($log->action_detail, 'เปลี่ยนสถานะ')) {
                $isSystem = true;
            }

            $messages[] = [
                'id' => $log->id,
                'user_id' => $log->user_id,
                'sender_name' => $log->user?->name ?? 'ระบบ',
                'sender_role' => $log->user?->role ?? 1,
                'type' => $isSystem ? 'system' : 'message',
                'text' => $log->note ?? $log->action_detail,
                'time' => $log->created_at->addYears(543)->locale('th')->isoFormat('D MMM YY, HH:mm') . ' น.'
            ];
        }

        return response()->json([
            'ticket_id' => $helpRequest->id,
            'status' => $helpRequest->status,
            'messages' => $messages
        ]);
    }

    /**
     * Store new chat message reply on a specific help ticket.
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'status' => 'nullable|string|in:pending,in_progress,resolved,rejected',
        ]);

        $helpRequest = \App\Models\HelpRequest::findOrFail($id);
        $user = auth()->user();

        // Security check
        if ($user->role == 1 && $helpRequest->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $oldStatus = $helpRequest->status;
        $newStatus = $request->status ?? $oldStatus;

        // If staff updates status
        if ($user->role > 1 && $newStatus !== $oldStatus) {
            $helpRequest->update(['status' => $newStatus]);
            
            $actionDetail = ($user->role == 4 ? 'ผู้ดูแลระบบ' : 'ครู') . " " . $user->name . " อัปเดตสถานะ";
            
            \App\Models\HelpRequestLog::create([
                'help_request_id' => $helpRequest->id,
                'user_id' => $user->id,
                'status' => $newStatus,
                'action_detail' => $actionDetail . " เป็น " . $this->getStatusThai($newStatus),
                'note' => "เปลี่ยนสถานะการดำเนินงานเป็น " . $this->getStatusThai($newStatus)
            ]);
        }

        // Create actual reply message log
        $actionDetail = ($user->role == 4 ? 'ผู้ดูแลระบบ' : ($user->role == 2 ? 'ครู' : 'ผู้เรียน')) . " " . $user->name . " ตอบกลับ";
        
        \App\Models\HelpRequestLog::create([
            'help_request_id' => $helpRequest->id,
            'user_id' => $user->id,
            'status' => $newStatus,
            'action_detail' => $actionDetail,
            'note' => $request->message
        ]);

        // Send FCM Web Push Notification
        try {
            if ($user->role == 1) {
                // Student replying -> Notify staff
                $notifyMsg = "มีข้อความใหม่จากผู้เรียน " . $user->name . ": \"" . Str::limit($request->message, 50) . "\"";
                \App\Models\HelpNotification::notifyStaff($helpRequest, $notifyMsg);
            } else {
                // Staff replying -> Notify Student
                $notifyMsg = "มีข้อความตอบกลับใหม่จากคุณครู/ผู้ดูแลระบบ: \"" . Str::limit($request->message, 50) . "\"";
                \App\Services\FcmService::sendPushNotification(
                    $helpRequest->user_id,
                    'ศูนย์รับแจ้งปัญหา (OLIS)',
                    $notifyMsg,
                    [
                        'ticket' => (string) $helpRequest->id,
                        'url' => route('help.index', ['ticket' => $helpRequest->id])
                    ]
                );
            }
        } catch (\Exception $e) {
            logger()->error("FCM Chat Dispatch Error: " . $e->getMessage());
        }

        return response()->json(['success' => true]);
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
