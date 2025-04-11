<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\print\StudentPaymentPrintController;
use App\Http\Controllers\StudentDashboard\StudentPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/', [AdminAuthController::class, 'login'])->name('index');

Route::get('/dashboard', function () {
    return redirect()->back();
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/roll', [RoleController::class, 'roll'])->name('admin.roll');
Route::get('/admin/permission', [PermissionController::class, 'permission'])->name('admin.permission');



// Route::get('/admin/student-create', [StudentController::class, 'create'])->name('admin.student.create');
// // Route::post('/admin/student-edit', [StudentController::class, 'edit'])->name('admin.student.edit');
// Route::get('/admin/student-edit/{studentId}', [StudentController::class, 'edit'])->name('admin.student.edit');
// Route::post('/admin/student-store', [StudentController::class, 'store'])->name('admin.student.store');
// Route::put('/admin/student-update/{studentId}', [StudentController::class, 'update'])->name('admin.student.update');
// Route::get('/admin/student-destroy/{studentId}', [StudentController::class, 'destroy'])->name('student.destroy');
// Route::get('/admin/students-status/{id}', [StudentController::class, 'changeActiveStatus'])->name('student.changeActiveStatus');

// Route::get('/admin/lecturer-create', [LecturerController::class, 'create'])->name('admin.lecturer.create');
// // Route::post('/admin/lecturer-edit', [LecturerController::class, 'edit'])->name('admin.lecturer.edit');
// Route::get('/admin/lecturer-edit/{lecturerId}', [LecturerController::class, 'edit'])->name('admin.lecturer.edit');
// Route::post('/admin/lecturer-store', [LecturerController::class, 'store'])->name('admin.lecturer.store');
// Route::put('/admin/lecturer-update/{lecturerId}', [LecturerController::class, 'update'])->name('admin.lecturer.update');
// Route::delete('/admin/lecturer-destroy/{lecturerId}', [LecturerController::class, 'destroy'])->name('lecturer.destroy');
// Route::get('/admin/lecturer-status/{id}', [LecturerController::class, 'changeActiveStatus'])->name('lecturer.changeActiveStatus');

// Route::get('/admin/staff-create', [StaffController::class, 'create'])->name('admin.staff.create');
// // Route::post('/admin/staff-edit', [StaffController::class, 'edit'])->name('admin.staff.edit');
// Route::get('/admin/staff-edit/{staffId}', [StaffController::class, 'edit'])->name('admin.staff.edit');
// Route::post('/admin/staff-store', [StaffController::class, 'store'])->name('admin.staff.store');
// Route::put('/admin/staff-update/{staffId}', [StaffController::class, 'update'])->name('admin.staff.update');
// Route::delete('/admin/staff-destroy/{staffId}', [StaffController::class, 'destroy'])->name('staff.destroy');
// Route::get('/admin/staff-status/{id}', [StaffController::class, 'changeActiveStatus'])->name('staff.changeActiveStatus');

// Route::get('/admin/Lecture-create', [LecturerController::class, 'create'])->name('admin.lecture.create');

// Route::get('/admin/Starf', [StaffController::class, 'index'])->name('admin.starf');
// Route::get('/admin/Starf-create', [StaffController::class, 'create'])->name('admin.starf.create');



// Route::get('/admin/event', [EventController::class, 'index'])->name('admin.event');
// Route::get('/admin/event-create', [EventController::class, 'create'])->name('admin.event.create');

Route::get('/print-preview', [StudentPaymentPrintController::class, 'index'])->name('print-preview');

require __DIR__ . '/auth.php';
