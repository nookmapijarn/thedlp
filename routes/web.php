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
// Teacher Route
Route::prefix('/teachers')->group(function () {
    Route::get('/', [TeachersController::class, 'index'])->name('tdashboard');
    Route::get('/tdashboard', [TeachersController::class, 'index'])->name('tdashboard');
});
// Student Route
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/examschedule', [ExamscheduleController::class, 'index'])->name('examschedule');
    Route::get('/final', [FinalController::class, 'index'])->name('final');
    Route::get('/studentregis', [StudentRegisController::class, 'index'])->name('studentregis');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
