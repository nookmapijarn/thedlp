<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortVideo;
use App\Models\ShortVideoLike;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShortVideoController extends Controller
{
    public function index(Request $request)
    {
        $startId = $request->query('id');
        
        // Fetch all shorts with eager loaded relationships
        $shorts = ShortVideo::with(['teacher', 'course', 'lessons'])->get();

        // 1. Get student's enrolled course IDs in order of latest enrollment
        $userId = Auth::id();
        $enrolledCourseIds = [];
        if ($userId) {
            $enrolledCourseIds = \App\Models\Enrollment::where('user_id', $userId)
                ->orderBy('enrollment_date', 'desc')
                ->pluck('course_id')
                ->toArray();
        }

        // 2. Sort shorts based on enrollment course priority
        $shorts = $shorts->sortBy(function($short) use ($enrolledCourseIds) {
            if ($short->course_id) {
                $pos = array_search($short->course_id, $enrolledCourseIds);
                if ($pos !== false) {
                    // Enrolled course: sort by latest enrollment first (pos = 0, 1, 2...)
                    return $pos;
                }
                // Other course: sort after enrolled courses
                return 10000 - $short->id; // Higher ID (latest) comes first within this group
            }
            // General short (no course): sort last
            return 20000 - $short->id; // Higher ID (latest) comes first within this group
        })->values();

        // 3. If a start ID is requested, prioritize it to the very front
        if ($startId) {
            $shorts = $shorts->sortBy(function($short) use ($startId) {
                return $short->id == $startId ? -1 : 1;
            })->values();
        }

        // 4. Fetch unique teachers who have uploaded shorts, sorted by their total likes count
        $teachers = \App\Models\User::whereIn('id', ShortVideo::distinct()->pluck('teacher_id'))
            ->select('id', 'name')
            ->addSelect([
                'total_likes' => ShortVideo::whereColumn('teacher_id', 'users.id')
                    ->selectRaw('COALESCE(sum(likes_count), 0)')
            ])
            ->orderBy('total_likes', 'desc')
            ->get();

        return view('students.shorts', compact('shorts', 'teachers'));
    }

    /**
     * Toggle like/unlike for a short video via AJAX.
     */
    public function toggleLike($id)
    {
        $short = ShortVideo::findOrFail($id);
        $userId = Auth::id();

        $like = ShortVideoLike::where('user_id', $userId)
            ->where('short_video_id', $short->id)
            ->first();

        if ($like) {
            $like->delete();
            $short->decrement('likes_count');
            $status = 'unliked';
        } else {
            ShortVideoLike::create([
                'user_id' => $userId,
                'short_video_id' => $short->id
            ]);
            $short->increment('likes_count');
            $status = 'liked';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'likes_count' => $short->likes_count
        ]);
    }

    /**
     * Increment the view count for a short video via AJAX.
     */
    public function incrementView($id)
    {
        $short = ShortVideo::findOrFail($id);
        $short->increment('views_count');

        return response()->json([
            'success' => true,
            'views_count' => $short->views_count
        ]);
    }

    /**
     * Display teacher dashboard for short video uploads and management.
     */
    public function teacherIndex()
    {
        $teacherId = Auth::id();
        $shorts = ShortVideo::where('teacher_id', $teacherId)->with('course')->latest()->get();
        // Retrieve all courses taught by this teacher for link dropdown
        $courses = Course::where('teacher_id', $teacherId)->get();

        return view('teachers.shorts', compact('shorts', 'courses'));
    }

    /**
     * Store a newly uploaded short video or image slideshow by a teacher.
     */
    public function teacherStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'course_id' => 'nullable|integer|exists:courses,id',
            'type' => 'required|string|in:video,images',
            'video' => 'required_if:type,video|file|mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska|max:51200', // Max 50MB
            'images' => 'required_if:type,images|array|min:1|max:10',
            'images.*' => 'file|image|mimes:jpeg,png,jpg,webp,gif|max:5120', // Max 5MB per image
            'audio' => 'nullable|file|mimes:mp3,wav,m4a,aac,oga,ogg,webm|max:10240', // Max 10MB
        ]);

        $videoPath = null;
        $imagesPaths = [];
        $audioPath = null;

        if ($request->type === 'video') {
            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $videoPath = $file->store('shorts', 'public');
            } else {
                return redirect()->back()->withErrors(['video' => 'ไม่พบไฟล์วิดีโอสำหรับการอัปโหลด']);
            }
        } else {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('shorts/images', 'public');
                    $imagesPaths[] = $path;
                }
            } else {
                return redirect()->back()->withErrors(['images' => 'ไม่พบรูปภาพสำหรับการอัปโหลด']);
            }

            // Store audio file if uploaded or voiceover recorded
            if ($request->hasFile('audio')) {
                $audioFile = $request->file('audio');
                $audioPath = $audioFile->store('shorts/audio', 'public');
            }
        }

        ShortVideo::create([
            'teacher_id' => Auth::id(),
            'course_id' => $request->course_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoPath,
            'images' => $request->type === 'images' ? $imagesPaths : null,
            'audio_path' => $audioPath,
        ]);

        return redirect()->back()->with('success', 'เผยแพร่คลิปเรียนรู้สั้นสำเร็จเรียบร้อยแล้ว');
    }

    /**
     * Update an existing short post by a teacher.
     */
    public function teacherUpdate(Request $request, $id)
    {
        $short = ShortVideo::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'course_id' => 'nullable|integer|exists:courses,id',
        ]);

        $short->update([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $request->course_id,
        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลคลิปเรียนรู้สั้นเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified short video from storage by a teacher.
     */
    public function teacherDestroy($id)
    {
        $short = ShortVideo::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $this->deletePhysicalFiles($short);

        $short->delete();

        return redirect()->back()->with('success', 'ลบคลิปเรียนรู้สั้นเรียบร้อยแล้ว');
    }

    /**
     * Display admin list of all shorts in the system for moderation.
     */
    public function adminIndex()
    {
        $shorts = ShortVideo::with(['teacher', 'course'])->latest()->paginate(15);
        return view('admin.shorts.index', compact('shorts'));
    }

    /**
     * Delete any short video by an admin (moderation).
     */
    public function adminDestroy($id)
    {
        $short = ShortVideo::findOrFail($id);

        $this->deletePhysicalFiles($short);

        $short->delete();

        return redirect()->back()->with('success', 'ผู้ดูแลระบบลบคลิปเรียนรู้สั้นเรียบร้อยแล้ว');
    }

    /**
     * Helper to delete files from storage.
     */
    private function deletePhysicalFiles(ShortVideo $short)
    {
        if ($short->type === 'video' && $short->video_path) {
            if (Storage::disk('public')->exists($short->video_path)) {
                Storage::disk('public')->delete($short->video_path);
            }
        } elseif ($short->type === 'images' && is_array($short->images)) {
            foreach ($short->images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        if ($short->audio_path) {
            if (Storage::disk('public')->exists($short->audio_path)) {
                Storage::disk('public')->delete($short->audio_path);
            }
        }
    }

    /**
     * Display a teacher profile page to students showing shorts, courses, and quizzes.
     */
    public function teacherProfile($id)
    {
        $teacher = \App\Models\User::where('id', $id)->firstOrFail();
        
        // Fetch all shorts by this teacher
        $shorts = ShortVideo::where('teacher_id', $id)->with(['course', 'lessons'])->latest()->get();
        
        // Fetch published courses by this teacher
        $courses = Course::where('teacher_id', $id)->where('is_published', true)->latest()->get();
        
        // Fetch active quizzes created by this teacher
        $quizzes = \Illuminate\Support\Facades\DB::table('quizzes')
            ->where('created_by', $id)
            ->where('is_active', 1)
            ->get();

        // Calculate total views and total likes
        $totalViews = $shorts->sum('views_count');
        $totalLikes = $shorts->sum('likes_count');

        return view('students.teacher_profile', compact('teacher', 'shorts', 'courses', 'quizzes', 'totalViews', 'totalLikes'));
    }

    /**
     * Retrieve comments and nested replies for a specific short video.
     */
    public function getComments($id)
    {
        $short = ShortVideo::findOrFail($id);
        $comments = \App\Models\ShortVideoComment::where('short_video_id', $id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg',
                    'comment' => $comment->comment,
                    'time' => $comment->created_at->diffForHumans(),
                    'replies' => $comment->replies->map(function($reply) {
                        return [
                            'id' => $reply->id,
                            'user_id' => $reply->user_id,
                            'user_name' => $reply->user->name,
                            'user_avatar' => $reply->user->avatar ? asset('storage/' . $reply->user->avatar) : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg',
                            'comment' => $reply->comment,
                            'time' => $reply->created_at->diffForHumans()
                        ];
                    })
                ];
            });

        return response()->json(['comments' => $comments]);
    }

    /**
     * Save a new comment or nested reply for a short video, and dispatch notifications.
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:short_video_comments,id'
        ]);

        $short = ShortVideo::findOrFail($id);
        $userId = Auth::id();

        $comment = \App\Models\ShortVideoComment::create([
            'short_video_id' => $short->id,
            'user_id' => $userId,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment
        ]);

        $commentUser = Auth::user();

        // Dispatch FCM Notifications
        try {
            if ($request->parent_id) {
                // Reply: Notify parent comment owner
                $parentComment = \App\Models\ShortVideoComment::findOrFail($request->parent_id);
                if ($parentComment->user_id !== $userId) {
                    \App\Services\FcmService::sendPushNotification(
                        $parentComment->user_id,
                        'มีคนตอบกลับความคิดเห็นของคุณ 💬',
                        "{$commentUser->name}: \"{$request->comment}\"",
                        [
                            'short_id' => (string) $short->id,
                            'url' => route('shorts.index', ['id' => $short->id])
                        ]
                    );
                }
            } else {
                // New comment: Notify short video owner
                if ($short->teacher_id !== $userId) {
                    \App\Services\FcmService::sendPushNotification(
                        $short->teacher_id,
                        'มีคนแสดงความเห็นบนคลิปสั้นของคุณ 💬',
                        "{$commentUser->name}: \"{$request->comment}\"",
                        [
                            'short_id' => (string) $short->id,
                            'url' => route('shorts.index', ['id' => $short->id])
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            logger()->error("Shorts comment notification failed: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'user_name' => $commentUser->name,
                'user_avatar' => $commentUser->avatar ? asset('storage/' . $commentUser->avatar) : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg',
                'comment' => $comment->comment,
                'time' => 'เมื่อสักครู่',
                'replies' => []
            ]
        ]);
    }
}
