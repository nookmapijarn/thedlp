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
        // 1. Validation: ปรับให้รับเป็น string (Base64)
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'cover_image_base64' => 'nullable|string', 
        ]);

        try {
            $coverFullUrl = null;
            $request->validate([
                'title' => 'required|string|max:255',
                'cover_image_base64' => 'nullable|string', // ตรวจสอบว่าชื่อตรงกับในหน้า View ไหม
            ]);
            // --- Logic การจัดการภาพ Cover (Base64) ---
            if ($request->input('cover_image_base64')) {
                $imageData = $request->input('cover_image_base64');
                
                // ตัดส่วนหัว Prefix ของ Base64 ออก
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = base64_decode($imageData);

                // ตั้งชื่อไฟล์: ใช้ชื่อคอร์ส (แบบ slug) หรือ ID ผสมกับเวลาเพื่อป้องกันชื่อซ้ำ
                $imageName = 'course_' . time() . '_' . uniqid() . '.png';
                
                // กำหนด Directory ภายใต้ public_path
                $directory = public_path('storage/images/course');

                // ตรวจสอบและสร้าง Folder หากยังไม่มี
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // บันทึกไฟล์ลง Disk
                file_put_contents($directory . '/' . $imageName, $imageData);
                
                // สร้าง URL เต็ม (Full Path) เพื่อเก็บลงฐานข้อมูลตามที่คุณออกแบบไว้
                $coverFullUrl = asset('storage/images/course/' . $imageName);
            }

            // --- บันทึกข้อมูล Course ---
            Course::create([
                'teacher_id' => Auth::id(),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price') ?? 0,
                'cover_image' => $coverFullUrl, // เก็บ URL เต็ม เช่น http://domain.com/storage/images/course/xxx.png
                'is_published' => 1,
            ]);

            return redirect()->route('courses.manage')->with('success', 'คอร์สถูกสร้างเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            \Log::error('Course Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึก: ' . $e->getMessage())
                ->withInput();
        }
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
        // 1. ตรวจสอบสิทธิ์
        if (Auth::id() !== $course->teacher_id) {
            return redirect()->route('courses.manage')->with('error', 'You do not have permission to update this course.');
        }

        // 2. Validation (เปลี่ยนจาก image เป็น string เพื่อรับ Base64)
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'cover_image_base64' => 'nullable|string', 
        ]);

        try {
            $coverFullUrl = $course->cover_image; // ใช้ค่าเดิมไว้ก่อน

            // --- Logic การจัดการภาพ Cover (Base64) เมื่อมีการส่งรูปใหม่มา ---
            if ($request->filled('cover_image_base64')) {
                
                // A. ลบไฟล์เก่าออกจาก Server (ถ้ามี)
                if ($course->cover_image) {
                    // แปลง URL เต็มกลับเป็น path ในเครื่อง
                    // เช่น จาก http://site.com/storage/images/course/abc.png -> storage/images/course/abc.png
                    $relativeContentPath = str_replace(asset('storage/'), '', $course->cover_image);
                    $oldFilePath = public_path('storage/' . $relativeContentPath);
                    
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // B. จัดการรูปภาพใหม่ (Base64)
                $imageData = $request->input('cover_image_base64');
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = base64_decode($imageData);

                $imageName = 'course_' . time() . '_' . uniqid() . '.png';
                $directory = public_path('storage/images/course');

                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // บันทึกไฟล์ใหม่
                file_put_contents($directory . '/' . $imageName, $imageData);
                
                // C. สร้าง Full URL สำหรับเก็บใน DB
                $coverFullUrl = asset('storage/images/course/' . $imageName);
            }

            // 3. อัปเดตข้อมูลลงฐานข้อมูล
            $course->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price') ?? 0,
                'cover_image' => $coverFullUrl,
            ]);

            return redirect()->route('courses.manage')->with('success', 'คอร์สถูกอัปเดตเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            \Log::error('Course Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการอัปเดต: ' . $e->getMessage())
                ->withInput();
        }
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

        return redirect()->route('courses.manage_modules', $lesson->module->course_id)
                    ->with('success', 'สร้างบทเรียนเรียบร้อยแล้ว');
    }

    // --- ฟังก์ชันสำหรับการแก้ไขและลบ (สามารถเพิ่มได้ตามต้องการ) ---
/**
     * อัปเดตชื่อ Module
     */
    public function updateModule(Request $request, Module $module)
    {
        if (Auth::id() !== $module->course->teacher_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ดำเนินการ');
        }

        $request->validate(['title' => 'required|string|max:255']);
        
        $module->update([
            'title' => $request->title
        ]);

        return redirect()->back()->with('success', 'แก้ไขโมดูลเรียบร้อยแล้ว');
    }

    /**
     * ลบ Module และจัดการลำดับใหม่
     */
    public function destroyModule(Module $module)
    {
        $course = $module->course;
        if (Auth::id() !== $course->teacher_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ดำเนินการ');
        }

        $deletedOrder = $module->order_number;
        $module->delete();

        // Re-order: ปรับลำดับโมดูลที่เหลือให้รันต่อกัน
        Module::where('course_id', $course->id)
            ->where('order_number', '>', $deletedOrder)
            ->decrement('order_number');

        return redirect()->back()->with('success', 'ลบโมดูลเรียบร้อยแล้ว');
    }

    // --- ส่วนการจัดการ Lessons (เพิ่มเติม) ---

    /**
     * แสดงหน้าแก้ไข Lesson
     */
    public function editLesson(Lesson $lesson)
    {
        if (Auth::id() !== $lesson->module->course->teacher_id) {
            return redirect()->route('courses.manage')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $quizzes = Quiz::where('created_by', Auth::id())->get();
        return view('teachers.courses.edit_lesson', compact('lesson', 'quizzes'));
    }

    /**
     * อัปเดตข้อมูล Lesson
     */
    public function updateLesson(Request $request, Lesson $lesson)
    {
        if (Auth::id() !== $lesson->module->course->teacher_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ดำเนินการ');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'quiz_id' => 'nullable|exists:quizzes,id',
        ]);

        $lesson->update([
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'quiz_id' => $request->quiz_id,
        ]);

        return redirect()->route('courses.manage_modules', $lesson->module->course_id)
                         ->with('success', 'อัปเดตบทเรียนเรียบร้อยแล้ว');
    }

    /**
     * ลบ Lesson และจัดการลำดับใหม่
     */
    public function destroyLesson(Lesson $lesson)
    {
        $moduleId = $lesson->module_id;
        if (Auth::id() !== $lesson->module->course->teacher_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ดำเนินการ');
        }

        $deletedOrder = $lesson->order_number;
        $lesson->delete();

        // Re-order: ปรับลำดับบทเรียนในโมดูลนั้นๆ
        Lesson::where('module_id', $moduleId)
            ->where('order_number', '>', $deletedOrder)
            ->decrement('order_number');

        return redirect()->back()->with('success', 'ลบบทเรียนเรียบร้อยแล้ว');
    }
}