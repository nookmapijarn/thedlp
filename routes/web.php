<?php

namespace App\Http\Controllers;

// Base
use App\Http\Controllers\ProfileController;

// Admin
use App\Http\Controllers\Admin\ZipUploadController;

// Stdudent
use App\Http\Controllers\Students\DashboardController;
use App\Http\Controllers\Students\ExamscheduleController;
use App\Http\Controllers\Students\FinalController;
use App\Http\Controllers\Students\StudentRegisController;

// Teacher
use App\Http\Controllers\Teachers\TeachersController;
use App\Http\Controllers\Teachers\TeachersGradeController;
use App\Http\Controllers\Teachers\TeachersStudentProfile;
use App\Http\Controllers\Teachers\TeachersScoreController;
use App\Http\Controllers\Teachers\TeachersReportController;

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



Route::get('/welcome/{roletype}', function () {
    return view('welcome');
})->name('/welcome');

Route::get('/welcome', function () {
    return view('welcome');
})->name('/welcome');

Route::get('/regis', function () {
    return view('welcome');
})->name('regis');

Route::get('/', function () {
    return view('welcome');
})->name('/');

// Admin
Route::get('/upload', function () {
    return view('admin.upload');
})->name('upload.form');

Route::post('/upload', [ZipUploadController::class, 'upload'])->name('zip.upload');


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
    });
});

// Student Route
Route::middleware('auth', 'verified')->group(function () {
    Route::middleware('checkrole:1')->group(function () {
        Route::get('/ประวัติการเรียน', [DashboardController::class, 'index'])->name('ประวัติการเรียน');
        Route::get('/ตารางสอบ', [ExamscheduleController::class, 'index'])->name('ตารางสอบ');
        Route::get('/การจบหลักสูตร', [FinalController::class, 'index'])->name('การจบหลักสูตร');
        Route::get('/ประวัติการลงทะเบียน', [StudentRegisController::class, 'index'])->name('ประวัติการลงทะเบียน');
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
