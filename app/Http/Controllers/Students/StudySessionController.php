<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudySession;
use Illuminate\Support\Facades\Auth;

class StudySessionController extends Controller
{
    /**
     * Handle heartbeat pings from course classroom or short videos.
     */
    public function ping(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();
        $courseId = $request->input('course_id');
        $shortVideoId = $request->input('short_video_id');
        $type = $request->input('type'); // 'classroom' or 'short_video'
        $sessionId = $request->input('session_id');

        if (!$type) {
            return response()->json(['success' => false, 'message' => 'Type is required'], 400);
        }

        $session = null;

        // Try to find an existing active session
        if ($sessionId) {
            $session = StudySession::where('id', $sessionId)
                ->where('user_id', $userId)
                ->first();
        }

        if (!$session) {
            // Create a new session
            $session = StudySession::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'short_video_id' => $shortVideoId,
                'type' => $type,
                'accessed_at' => now(),
                'exited_at' => now(),
                'duration' => 0,
            ]);
        } else {
            // Update existing session: increment duration by the ping interval
            $lastExited = $session->exited_at ? \Carbon\Carbon::parse($session->exited_at) : now();
            $diffSeconds = now()->diffInSeconds($lastExited);

            // Cap the added seconds to prevent fraud (e.g. if the ping was delayed or simulated)
            $addedSeconds = ($diffSeconds > 0 && $diffSeconds < 60) ? $diffSeconds : 15;

            $session->exited_at = now();
            $session->duration = $session->duration + $addedSeconds;
            $session->save();
        }

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'duration' => $session->duration,
        ]);
    }
}
