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
        $shorts = ShortVideo::with(['teacher', 'course', 'lessons'])->latest()->get();

        if ($startId) {
            $shorts = $shorts->sortBy(function($short) use ($startId) {
                return $short->id == $startId ? 0 : 1;
            })->values();
        }

        return view('students.shorts', compact('shorts'));
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
}
