<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'help_request_id',
        'message',
        'is_read',
    ];

    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Send notification to a specific user.
     */
    public static function sendNotification($userId, $helpRequestId, $message)
    {
        if (auth()->check() && $userId == auth()->id()) {
            return null;
        }

        $notification = self::create([
            'user_id' => $userId,
            'help_request_id' => $helpRequestId,
            'message' => $message,
            'is_read' => false,
        ]);

        // Trigger Web Push Notification via Google FCM
        try {
            \App\Services\FcmService::sendPushNotification(
                $userId,
                'ศูนย์รับแจ้งปัญหา (OLIS)',
                $message,
                [
                    'ticket' => (string) $helpRequestId,
                    'url' => route('notifications.read', ['id' => $notification->id])
                ]
            );
        } catch (\Exception $e) {
            logger()->error("FCM Dispatch Error: " . $e->getMessage());
        }

        return $notification;
    }

    /**
     * Notify all teachers (role 2) and admins (role 4) about a help request event.
     */
    public static function notifyStaff($helpRequest, $message)
    {
        $staff = User::whereIn('role', [2, 4])->get();
        foreach ($staff as $user) {
            self::sendNotification($user->id, $helpRequest->id, $message);
        }
    }
}
