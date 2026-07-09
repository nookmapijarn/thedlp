<?php

namespace App\Http\Controllers;

use App\Models\HelpNotification;
use Illuminate\Http\Request;

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
}
