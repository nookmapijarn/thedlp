<?php
// Base
use App\Http\Controllers\ProfileController;

// Stdudent
use App\Http\Controllers\Students\DashboardController;
use App\Http\Controllers\Students\ExamscheduleController;
use App\Http\Controllers\Students\FinalController;
use App\Http\Controllers\Students\StudentRegisController;

// Teacher
use App\Http\Controllers\Teachers\TeachersController;

// Boss
use App\Http\Controllers\Boss\BossController;

// Help
use App\Http\Controllers\Help\HelpController;
use App\Http\Controllers\Help\NewStudentController;
use App\Http\Controllers\Help\TrackStudentController;
use App\Http\Controllers\Help\ContactTeacherController;

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

Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('welcome');
});
// Boss Route
Route::prefix('/boss')->group(function () {
    Route::get('/', [BossController::class, 'index'])->name('boss');
    Route::get('/bdashboard', [BossController::class, 'index'])->name('boss');
});
// Teacher Route
Route::prefix('/teachers')->group(function () {
    Route::get('/', [TeachersController::class, 'index'])->name('tdashboard');
    Route::get('/tdashboard', [TeachersController::class, 'index'])->name('tdashboard');
});
// Help Route
Route::prefix('/help')->group(function () {
    Route::get('/', [HelpController::class, 'index'])->name('hdashboard');
    Route::get('/hdashboard', [HelpController::class, 'index'])->name('hdashboard');
    Route::get('/สมัครเรียน', [NewStudentController::class, 'index'])->name('สมัครเรียน');
    Route::get('/ติดตามผู้จบ', [TrackStudentController::class, 'create'])->name('ติดตามผู้จบ');
    Route::get('/ติดต่อครู', [ContactTeacherController::class, 'index'])->name('ติดต่อครู');
});
// Student Route
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/ประวัติการเรียน', [DashboardController::class, 'index'])->name('ประวัติการเรียน');
    Route::get('/ตารางสอบ', [ExamscheduleController::class, 'index'])->name('ตารางสอบ');
    Route::get('/การจบหลักสูตร', [FinalController::class, 'index'])->name('การจบหลักสูตร');
    Route::get('/ประวัติการลงทะเบียน', [StudentRegisController::class, 'index'])->name('ประวัติการลงทะเบียน');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
