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
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AdminHelpRequestController;


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
use App\Http\Controllers\Students\HelpCenterController;

// Teacher
use App\Http\Controllers\Teachers\TeachersController;
use App\Http\Controllers\Teachers\TeachersGradeController;
use App\Http\Controllers\Teachers\TeachersStudentProfile;
use App\Http\Controllers\Teachers\TeachersScoreController;
use App\Http\Controllers\Teachers\TeachersReportController;
use App\Http\Controllers\Teachers\TeachersTestController;
use App\Http\Controllers\Teachers\TeachersBookController;
use App\Http\Controllers\Teachers\TeachersCourseController;
use App\Http\Controllers\Teachers\TeacherHelpRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ShortVideoController;

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
        Route::get('/auditlogs', [AuditLogController::class, 'index'])->name('admin.audit_logs');
        Route::get('/help-requests', [AdminHelpRequestController::class, 'index'])->name('admin.help.index');
        Route::post('/help-requests/{id}/reply', [AdminHelpRequestController::class, 'reply'])->name('admin.help.reply');
        Route::patch('/help-requests/{id}/status', [AdminHelpRequestController::class, 'updateStatus'])->name('admin.help.status');

        // OLIS Shorts for Admin
        Route::get('/shorts', [ShortVideoController::class, 'adminIndex'])->name('admin.shorts.index');
        Route::delete('/shorts/{id}', [ShortVideoController::class, 'adminDestroy'])->name('admin.shorts.destroy');

        // Online Petitions for Admin
        Route::get('/petitions', [PetitionController::class, 'adminIndex'])->name('admin.petitions.index');
        Route::put('/petitions/{id}', [PetitionController::class, 'adminUpdate'])->name('admin.petitions.update');
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
        Route::put('/modules/{module}', [TeachersCourseController::class, 'updateModule'])->name('modules.update');
        Route::delete('/modules/{module}', [TeachersCourseController::class, 'destroyModule'])->name('modules.destroy');
        Route::get('/lessons/{lesson}/edit', [TeachersCourseController::class, 'editLesson'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [TeachersCourseController::class, 'updateLesson'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [TeachersCourseController::class, 'destroyLesson'])->name('lessons.destroy');
        
        // ศูนย์ช่วยเหลือผู้เรียนสำหรับคุณครู
        Route::get('/help-requests', [TeacherHelpRequestController::class, 'index'])->name('teachers.help.index');
        Route::post('/help-requests/{id}/reply', [TeacherHelpRequestController::class, 'reply'])->name('teachers.help.reply');
        Route::patch('/help-requests/{id}/status', [TeacherHelpRequestController::class, 'updateStatus'])->name('teachers.help.status');

        // OLIS Shorts for Teachers
        Route::get('/shorts', [ShortVideoController::class, 'teacherIndex'])->name('teachers.shorts.index');
        Route::post('/shorts', [ShortVideoController::class, 'teacherStore'])->name('teachers.shorts.store');
        Route::delete('/shorts/{id}', [ShortVideoController::class, 'teacherDestroy'])->name('teachers.shorts.destroy');
        Route::put('/shorts/{id}', [ShortVideoController::class, 'teacherUpdate'])->name('teachers.shorts.update');
        });
});

// Student Route
Route::middleware('auth', 'verified')->group(function () {
    Route::middleware('checkrole:1')->group(function () {
        Route::get('/home', [DashboardController::class, 'home'])->name('home');

        // OLIS Shorts for Students
        Route::get('/shorts', [ShortVideoController::class, 'index'])->name('shorts.index');
        Route::post('/shorts/{id}/like', [ShortVideoController::class, 'toggleLike'])->name('shorts.like');
        Route::post('/shorts/{id}/view', [ShortVideoController::class, 'incrementView'])->name('shorts.view');

        Route::get('/ประวัติการเรียน', [DashboardController::class, 'index'])->name('ประวัติการเรียน');
        Route::get('/ตารางสอบ', [ExamscheduleController::class, 'index'])->name('ตารางสอบ');
        Route::get('/การจบหลักสูตร', [FinalController::class, 'index'])->name('การจบหลักสูตร');
        Route::get('/การลงทะเบียน', [StudentRegisController::class, 'index'])->name('การลงทะเบียน');
        Route::get('/กพช', [ActivityController::class, 'index'])->name('กพช');
        Route::get('/คำร้องออนไลน์', [PetitionController::class, 'index'])->name('คำร้องออนไลน์');
        Route::post('/คำร้องออนไลน์', [PetitionController::class, 'store'])->name('คำร้องออนไลน์.store');
        // ระบบทดสอบ
        Route::get('/ทดสอบออนไลน์', [ExamController::class, 'index'])->name('ทดสอบออนไลน์');
        Route::post('/quizzes/{id}/initialize', [ExamController::class, 'initializeAttempt'])->name('quizzes.initialize');
        Route::get('/quizzes/{id}/start', [ExamController::class, 'startQuiz'])->name('quizzes.start');
        Route::post('/quizzes/{id}/submit', [ExamController::class, 'submitQuiz'])->name('quizzes.submit');
        Route::get('student/get-cert-base64', [ExamController::class, 'getCertificateBase64'])->name('cert.base64');
        // สื่อ
        Route::get('/สื่อการเรียนรู้', [MediaController::class, 'index'])->name('สื่อการเรียนรู้');
        // ศูนย์รับแจ้งปัญหาผู้เรียน
        Route::get('/ศูนย์รับแจ้งปัญหา', [HelpCenterController::class, 'index'])->name('help.index');
        Route::post('/ศูนย์รับแจ้งปัญหา', [HelpCenterController::class, 'store'])->name('help.store');
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
        Route::post('/olisai/clear', [OlisAiController::class, 'clearHistory'])->name('olisai.clear');
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
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read_all');
    Route::get('/avatar/update', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::post('/avatar/update', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/fcm-token/store', [FcmController::class, 'store'])->name('fcm.store');
    
    // Chat endpoints for Real-time Helpdesk System
    Route::get('/help-requests/{id}/messages', [NotificationController::class, 'getMessages'])->name('help.messages');
    Route::post('/help-requests/{id}/messages', [NotificationController::class, 'sendMessage'])->name('help.send_message');
});

Route::get('/privacy-policy', function () {
    return view('policy.privacy');
})->name('policy.privacy');

Route::get('/cookie-policy', function () {
    return view('policy.cookie');
})->name('policy.cookie');

Route::get('/offline', function () {
    return view('errors.offline');
})->name('offline');

require __DIR__.'/auth.php';
