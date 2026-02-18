<?php

namespace App\Http\Controllers;

// Base
use App\Http\Controllers\ProfileController;

// Welcome
use App\Http\Controllers\WelcomeController;

// Admin
use App\Http\Controllers\Admin\ZipUploadController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DatareviweController;


// Stdudent
use App\Http\Controllers\Students\DashboardController;
use App\Http\Controllers\Students\ExamscheduleController;
use App\Http\Controllers\Students\FinalController;
use App\Http\Controllers\Students\StudentRegisController;
use App\Http\Controllers\Students\ActivityController;
use App\Http\Controllers\Students\ClassroomController;
use App\Http\Controllers\Students\OlisAiController;
use App\Http\Controllers\Students\ExamController;
use App\Http\Controllers\Students\MediaController;
use App\Http\Controllers\Students\PetitionController;

// Teacher
use App\Http\Controllers\Teachers\TeachersController;
use App\Http\Controllers\Teachers\TeachersGradeController;
use App\Http\Controllers\Teachers\TeachersStudentProfile;
use App\Http\Controllers\Teachers\TeachersScoreController;
use App\Http\Controllers\Teachers\TeachersReportController;
use App\Http\Controllers\Teachers\TeachersTestController;
use App\Http\Controllers\Teachers\TeachersBookController;
use App\Http\Controllers\Teachers\TeachersCourseController;

// Boss
use App\Http\Controllers\Boss\BossController;

// Help
use App\Http\Controllers\Help\HelpController;
use App\Http\Controllers\Help\NewStudentController;
use App\Http\Controllers\Help\TrackStudentController;
use App\Http\Controllers\Help\ContactTeacherController;
use App\Http\Controllers\Help\FinalCheckController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Welcome
Route::get('/', [WelcomeController::class, 'index']);
Route::get('/welcome', [WelcomeController::class, 'index']);
Route::get('/olis', [WelcomeController::class, 'index']);
Route::get('/regis', [WelcomeController::class, 'index']);

// Admin
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('checkrole:4')->prefix('/admin')->group(function () {
        Route::get('/', [ZipUploadController::class, 'index']);
        Route::get('/adminuser', [AdminUserController::class, 'index'])->name('adminuser');
        Route::get('/adminregister', [AdminUserController::class, 'index'])->name('adminregister');
        Route::post('/adminregister', [AdminUserController::class, 'store'])->name('adminregister');
        Route::get('/adashboard', [ZipUploadController::class, 'index'])->name('admin');
        Route::get('/upload', [ZipUploadController::class, 'upload'])->name('zip.upload');
        Route::post('/upload', [ZipUploadController::class, 'upload'])->name('zip.upload');
        Route::get('/clearTable', [ZipUploadController::class, 'clearTable'])->name('clearTable');
        Route::post('/clearTable', [ZipUploadController::class, 'clearTable'])->name('clearTable');
        Route::get('/clearDateModifiled', [ZipUploadController::class, 'clearDateModifiled'])->name('clearDateModifiled');
        Route::post('/clearDateModifiled', [ZipUploadController::class, 'clearDateModifiled'])->name('clearDateModifiled');
        Route::get('/adminuserupdate', [AdminUserController::class, 'update'])->name('adminuserupdate');
        Route::post('/adminuserupdate', [AdminUserController::class, 'update'])->name('adminuserupdate');
        Route::get('/adminuserremove', [AdminUserController::class, 'remove'])->name('adminuserremove');
        Route::post('/adminuserremove', [AdminUserController::class, 'remove'])->name('adminuserremove');
        Route::match(['get', 'post'], '/admin/datareview', [DatareviweController::class, 'index'])->name('datareview');
    });
});



// Route::get('/upload', function () {
//     return view('admin.upload');
// })->name('upload.form');



// Boss Route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('checkrole:3')->prefix('/boss')->group(function () {
        Route::get('/', [BossController::class, 'index']);
        Route::get('/bdashboard', [BossController::class, 'index'])->name('boss');
    });
});


// Teacher Route
Route::middleware('auth', 'verified')->group(function () {
    Route::middleware('checkrole:2')->prefix('/teachers')->group(function () {
        Route::get('/', [TeachersController::class, 'index']);
        Route::get('/tdashboard', [TeachersController::class, 'index'])->name('tdashboard');
        Route::get('/treport', [TeachersReportController::class, 'index'])->name('treport');
        Route::post('/treport', [TeachersReportController::class, 'index'])->name('treport');
        Route::get('/tgrade', [TeachersGradeController::class, 'index'])->name('tgrade');
        Route::get('/tstudentprofile', [TeachersStudentProfile::class, 'index'])->name('tstudentprofile');
        Route::post('/tstudentprofile', [TeachersStudentProfile::class, 'index'])->name('tstudentprofile');
        Route::get('/tscore', [TeachersScoreController::class, 'index'])->name('tscore');
        Route::post('/tscore', [TeachersScoreController::class, 'index'])->name('tscore');
        // กลุ่มจัดการแบบทดสอบ
        Route::get('/ttest', [TeachersTestController::class, 'index'])->name('ttest.index');
        Route::get('/ttest/create', [TeachersTestController::class, 'create'])->name('ttest.create');
        Route::post('/ttest', [TeachersTestController::class, 'store'])->name('ttest.store');
        Route::get('/ttest/{id}/edit', [TeachersTestController::class, 'edit'])->name('ttest.edit');
        Route::put('/ttest/{id}', [TeachersTestController::class, 'update'])->name('ttest.update');
        Route::delete('/ttest/{id}', [TeachersTestController::class, 'destroy'])->name('ttest.destroy');
        Route::get('/api/subjects', [TeachersTestController::class, 'getSubjects'])->name('api.subjects');
        Route::get('/subjects/search', [TeachersTestController::class, 'getSubjects'])->name('ttest.get_subjects');
        Route::patch('/ttest/{quiz}/toggle', [TeachersTestController::class, 'toggle'])->name('ttest.toggle');
        Route::get('/quizzes/{id}/assign', [TeachersTestController::class, 'assignView'])->name('ttest.assign');
        Route::post('/quizzes/{id}/assign', [TeachersTestController::class, 'assignStore'])->name('ttest.assign.store');
        Route::get('/get-cert-base64', [TeachersTestController::class, 'getCertificateBase64'])->name('cert.base64');
        // หน้าภาพรวมของแบบทดสอบ (ที่คุณต้องการล่าสุด)
        Route::get('/quiz/{id}', [TeachersTestController::class, 'quizReport'])->name('ttest.report.summary');
        Route::post('/teachers/delete-student-attempts', [TeachersTestController::class, 'deleteStudentAttempts'])->name('attempts.delete_all');        // หน้ารายงานรายคน (ถ้าต้องการเจาะลึก)
        Route::get('/attempt/{id}', [TeachersTestController::class, 'showIndividualReport'])->name('ttest.report.individual');
        // กลุ่มจัดการหนังสือ
        Route::get('/tbooks', [TeachersBookController::class, 'index'])->name('books.index');
        Route::get('/tbooks/create', [TeachersBookController::class, 'create'])->name('books.create');
        Route::post('/tbooks', [TeachersBookController::class, 'store'])->name('books.store');
        // Routes สำหรับการจัดการคอร์ส
        Route::get('/teachers/courses/create', [TeachersCourseController::class, 'create'])->name('courses.create');
        Route::post('/teachers/courses/store', [TeachersCourseController::class, 'store'])->name('courses.store');
        Route::get('/teachers/courses/dashboard', [TeachersCourseController::class, 'manage'])->name('courses.manage');
        Route::get('/teachers/courses/{course}/edit', [TeachersCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/teachers/courses/{course}', [TeachersCourseController::class, 'update'])->name('courses.update');
        Route::delete('/teachers/courses/{course}', [TeachersCourseController::class, 'destroy'])->name('courses.destroy');
        // Routes สำหรับจัดการ Modules และ Lessons ใน Course
        Route::get('/teachers/courses/{course}/manage-modules', [TeachersCourseController::class, 'manageModules'])->name('courses.manage_modules');
        Route::post('/teachers/courses/{course}/modules', [TeachersCourseController::class, 'storeModule'])->name('courses.store_module');
        Route::get('/teachers/modules/{module}/create-lesson', [TeachersCourseController::class, 'createLesson'])->name('modules.create_lesson');
        Route::post('/teachers/modules/{module}/lessons', [TeachersCourseController::class, 'storeLesson'])->name('modules.store_lesson');
        });
});

// Student Route
Route::middleware('auth', 'verified')->group(function () {
    Route::middleware('checkrole:1')->group(function () {
        Route::get('/ประวัติการเรียน', [DashboardController::class, 'index'])->name('ประวัติการเรียน');
        Route::get('/ตารางสอบ', [ExamscheduleController::class, 'index'])->name('ตารางสอบ');
        Route::get('/การจบหลักสูตร', [FinalController::class, 'index'])->name('การจบหลักสูตร');
        Route::get('/การลงทะเบียน', [StudentRegisController::class, 'index'])->name('การลงทะเบียน');
        Route::get('/กพช', [ActivityController::class, 'index'])->name('กพช');
        Route::get('/คำร้องออนไลน์', [PetitionController::class, 'index'])->name('คำร้องออนไลน์');
        // ระบบทดสอบ
        Route::get('/ทดสอบออนไลน์', [ExamController::class, 'index'])->name('ทดสอบออนไลน์');
        Route::post('/quizzes/{id}/initialize', [ExamController::class, 'initializeAttempt'])->name('quizzes.initialize');
        Route::get('/quizzes/{id}/start', [ExamController::class, 'startQuiz'])->name('quizzes.start');
        Route::post('/quizzes/{id}/submit', [ExamController::class, 'submitQuiz'])->name('quizzes.submit');
        Route::get('/get-cert-base64', [ExamController::class, 'getCertificateBase64'])->name('cert.base64');
        // สื่อ
        Route::get('/สื่อการเรียนรู้', [MediaController::class, 'index'])->name('สื่อการเรียนรู้');
        // Routes for students to view and enroll in courses
        Route::get('/เรียนออนไลน์', [ClassroomController::class, 'index'])->name('เรียนออนไลน์');
        Route::get('/ห้องเรียน', [ClassroomController::class, 'enterRoom'])->name('ห้องเรียน');
        Route::get('/courses', [ClassroomController::class, 'index'])->name('classroom.index');
        Route::get('/courses/{course}', [ClassroomController::class, 'show'])->name('classroom.show');
        Route::post('/courses/{course}/enroll', [ClassroomController::class, 'enroll'])->name('classroom.enroll');
        Route::get('/courses/{course}/access', [ClassroomController::class, 'accessCourse'])->name('classroom.access');
        Route::put('/lessons/{lesson}/complete', [ClassroomController::class, 'markAsCompleted'])->name('lessons.complete');
        // AI OLIS
        Route::get('/olisai', [OlisAiController::class, 'index'])->name('olisai');
        Route::post('/olisai/chat', [OlisAiController::class, 'chat'])->name('olisai.chat');
        });
});

// Help Route
Route::prefix('/help')->group(function () {
    Route::get('/', [HelpController::class, 'index']);
    Route::get('/hdashboard', [HelpController::class, 'index'])->name('hdashboard');
    Route::get('/สมัครเรียน', [NewStudentController::class, 'index'])->name('สมัครเรียน');
    Route::post('/ติดตามผู้จบ.store', [TrackStudentController::class, 'store'])->name('ติดตามผู้จบ.store');
    Route::get('/ติดตามผู้จบ', [TrackStudentController::class, 'index'])->name('ติดตามผู้จบ');
    Route::get('/ติดต่อครู', [ContactTeacherController::class, 'index'])->name('ติดต่อครู');
    Route::get('/ผู้จบหลักสูตร', [FinalCheckController::class, 'index'])->name('ผู้จบหลักสูตร');
});  


Route::middleware('auth')->group(function () {
    Route::get('/avatar/update', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::post('/avatar/update', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
