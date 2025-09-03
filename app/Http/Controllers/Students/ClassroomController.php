<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson; // <-- Add this line
use App\Models\LessonCompletion; // <-- Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    /**
     * Display a list of all publicly available courses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $courses = Course::with('teacher')
                         ->where('is_published', true)
                         ->latest()
                         ->get();

        return view('students.classroom.index', compact('courses'));
    }

    /**
     * Display the details of a specific course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Course $course)
    {
        if (!$course->is_published) {
            abort(404); // Or redirect to a 404 page
        }

        // Eager load modules and lessons for better performance
        $course->load('modules.lessons', 'teacher');

        // Check if the current user is already enrolled
        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())
                                    ->where('course_id', $course->id)
                                    ->exists();
        }

        return view('students.classroom.show', compact('course', 'isEnrolled'));
    }

    /**
     * Handle the course enrollment process for an authenticated user.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enroll(Course $course)
    {
        // 1. Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to enroll in this course.');
        }
        
        // 2. Check if the user is already enrolled
        $isEnrolled = Enrollment::where('user_id', Auth::id())
                                ->where('course_id', $course->id)
                                ->exists();

        if ($isEnrolled) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // 3. Create a new enrollment record
        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'enrollment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'You have successfully enrolled in this course!');
    }

    /**
     * Handle student access to the course content.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function accessCourse(Course $course)
    {
        if (!Auth::check() || !Auth::user()->isEnrolledIn($course)) {
            return redirect()->route('classroom.show', $course->id)->with('error', 'คุณยังไม่ได้ลงทะเบียนในหลักสูตรนี้');
        }

        $user = Auth::user();

        // 1. ดึงข้อมูลบทเรียนที่ผู้ใช้ทำเสร็จแล้วทั้งหมด
        $completedLessons = $user->lessonCompletions()
                                ->whereHas('lesson.module', function ($query) use ($course) {
                                    $query->where('course_id', $course->id);
                                })
                                ->get(); // ใช้ get() เพื่อให้ได้ Collection

        // 2. คำนวณ Progress
        $totalLessons = $course->lessons->count();
        $completedCount = $completedLessons->count();
        $progress = ($totalLessons > 0) ? ($completedCount / $totalLessons) * 100 : 0;

        // 3. ส่งข้อมูลทั้งหมดไปยัง view
        return view('students.classroom.access', [
            'course' => $course,
            'progress' => round($progress),
            'completedLessons' => $completedLessons, // <-- ส่งตัวแปรนี้ไป
        ]);
    }

    public function markAsCompleted(Lesson $lesson)
    {
        $user = Auth::user();

        if (!$user->isEnrolledIn($lesson->module->course)) {
            return response()->json(['success' => false, 'message' => 'คุณไม่ได้ลงทะเบียนในหลักสูตรนี้'], 403);
        }

        $completion = LessonCompletion::firstOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        if ($completion->wasRecentlyCreated || is_null($completion->completed_at)) {
            $completion->completed_at = now();
            $completion->save();
        }

        // คำนวณ progress ใหม่
        $course = $lesson->module->course;
        $totalLessons = $course->lessons->count();
        $completedCount = $user->lessonCompletions()
                            ->whereHas('lesson.module', function ($query) use ($course) {
                                $query->where('course_id', $course->id);
                            })
                            ->count();
                            
        $progress = ($totalLessons > 0) ? ($completedCount / $totalLessons) * 100 : 0;
        
        // ส่ง JSON กลับไปพร้อมกับค่า progress
        return response()->json([
            'success' => true,
            'message' => 'ทำเครื่องหมายว่าเรียนจบแล้ว!',
            'progress' => round($progress)
        ]);
    }
}