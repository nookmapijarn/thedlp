<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

// Import Eloquent Models
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;

class TeachersCourseController extends Controller
{
    /**
     * แสดงหน้าแดชบอร์ดของครู พร้อมรายการคอร์สทั้งหมดที่สร้างโดยผู้ใช้ปัจจุบัน
     *
     * @return \Illuminate\View\View
     */
    public function manage()
    {
        $courses = Course::where('teacher_id', Auth::id())->get();
        return view('teachers.courses.dashboard', compact('courses'));
    }
    
    /**
     * แสดงฟอร์มสำหรับสร้างคอร์สใหม่
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('teachers.courses.create');
    }

    /**
     * บันทึกคอร์สใหม่ลงในฐานข้อมูล
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $coverImageName = null;
        if ($request->hasFile('cover_image')) {
            // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
            $file = $request->file('cover_image');
            $coverImageName = uniqid() . '.' . $file->getClientOriginalExtension();
            
            // กำหนดโฟลเดอร์ปลายทางและสร้างโฟลเดอร์ถ้ายังไม่มี
            $destinationPath = public_path('storage/images/covers');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // ย้ายไฟล์ไปยังโฟลเดอร์ที่ต้องการ
            $file->move($destinationPath, $coverImageName);
        }

        Course::create([
            'teacher_id' => Auth::id(),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'cover_image' => 'images/covers/' . $coverImageName, // บันทึกเฉพาะเส้นทางที่สัมพันธ์กับ public
        ]);

        return redirect()->route('courses.manage')->with('success', 'คอร์สถูกสร้างเรียบร้อยแล้ว');
    }
    /**
     * Show the form for editing an existing course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function edit(Course $course)
    {
        // Check if the current user is the owner of the course
        if (Auth::id() !== $course->teacher_id) {
            return redirect()->route('courses.manage')->with('error', 'You do not have permission to edit this course.');
        }

        return view('teachers.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, Course $course)
    {
        // ... (ส่วนตรวจสอบสิทธิ์และ validation)
        if (Auth::id() !== $course->teacher_id) {
            return redirect()->route('courses.manage')->with('error', 'You do not have permission to update this course.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coverImageName = $course->cover_image; // ใช้ชื่อไฟล์เดิมเป็นค่าเริ่มต้น
        if ($request->hasFile('cover_image')) {
            // สร้างชื่อไฟล์ใหม่และย้ายไฟล์
            $file = $request->file('cover_image');
            $newCoverImageName = uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/images/course');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $newCoverImageName);

            // ลบไฟล์เก่า
            if ($course->cover_image) {
                $oldImagePath = public_path('storage/' . $course->cover_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $coverImageName = 'images/course/' . $newCoverImageName;
        }

        $course->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'cover_image' => $coverImageName,
        ]);

        return redirect()->route('courses.manage')->with('success', 'คอร์สถูกอัปเดนเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified course from the database.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $course)
    {
        // Check if the current user is the owner
        if (Auth::id() !== $course->teacher_id) {
            return redirect()->route('courses.manage')->with('error', 'You do not have permission to delete this course.');
        }

        $course->delete();

        return redirect()->route('courses.manage')->with('success', 'Course deleted successfully!');
    }

    // --- ส่วนการจัดการ Modules ---

    /**
     * แสดงหน้าจัดการ Modules และ Lessons สำหรับคอร์สที่กำหนด
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function manageModules(Course $course)
    {
        if (Auth::id() !== $course->teacher_id) {
            return redirect()->route('courses.manage')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $modules = $course->modules()->with('lessons.quiz')->orderBy('order_number')->get();
        
        return view('teachers.courses.manage_modules', compact('course', 'modules'));
    }

    /**
     * จัดการการเพิ่ม Module ใหม่ในคอร์ส
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeModule(Request $request, Course $course)
    {
        if (Auth::id() !== $course->teacher_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ดำเนินการ');
        }

        $request->validate(['title' => 'required|string|max:255']);
        
        // คำนวณลำดับที่ปลอดภัย
        $lastOrder = Module::where('course_id', $course->id)->max('order_number');
        $orderNumber = ($lastOrder ? $lastOrder + 1 : 1);

        $module = new Module([
            'course_id' => $course->id, // ใช้ค่า Primary Key ของออบเจกต์ Course
            'title' => $request->title,
            'order_number' => $orderNumber,
        ]);
        $module->save();

        return redirect()->back()->with('success', 'โมดูลถูกเพิ่มเรียบร้อยแล้ว');
    }

    // --- ส่วนการจัดการ Lessons ---

    /**
     * แสดงฟอร์มสำหรับสร้าง Lesson ใหม่ใน Module
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\View\View
     */
    public function createLesson(Module $module)
    {
        if (Auth::id() !== $module->course->teacher_id) {
            return redirect()->route('courses.manage')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $quizzes = Quiz::where('created_by', Auth::id())->get();

        return view('teachers.courses.create_lesson', compact('module', 'quizzes'));
    }

    /**
     * บันทึก Lesson ใหม่ลงในฐานข้อมูล
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLesson(Request $request, Module $module)
    {
        if (Auth::id() !== $module->course->teacher_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ดำเนินการ');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'quiz_id' => 'nullable|exists:quizzes,id',
        ]);

        $lesson = new Lesson([
            'module_id' => $module->id,
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'quiz_id' => $request->quiz_id,
            'order_number' => Lesson::where('module_id', $module->id)->count() + 1,
        ]);
        $lesson->save();

        return redirect()->back()->with('success', 'บทเรียนถูกเพิ่มเรียบร้อยแล้ว');
    }

    // --- ฟังก์ชันสำหรับการแก้ไขและลบ (สามารถเพิ่มได้ตามต้องการ) ---
    // public function edit(Course $course) {}
    // public function update(Request $request, Course $course) {}
    // public function destroy(Course $course) {}
    // public function destroyModule(Module $module) {}
    // public function destroyLesson(Lesson $lesson) {}
}