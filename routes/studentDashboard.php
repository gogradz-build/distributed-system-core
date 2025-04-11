<?php

use App\Http\Controllers\StudentDashboard\CourceViewController;
use App\Http\Controllers\StudentDashboard\StudentAssignmentController;
use App\Http\Controllers\StudentDashboard\StudentDashboardController;
use App\Http\Controllers\StudentDashboard\StudentExamResultController;
use App\Http\Controllers\StudentDashboard\StudentPaymentController;
use App\Http\Controllers\StudentDashboard\StudentProfileController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;



//Auth
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
Route::get('/student/courses', [CourceViewController::class, 'index'])->name('student.courses');
// Route::get('/student/courses/content', [CourceViewController::class, 'content'])->name('student.course.content');
Route::get('/student/payments', [StudentPaymentController::class, 'index'])->name('student.payment');
Route::get('/student/assignment', [StudentAssignmentController::class, 'index'])->name('student.assignment');

// Route::get('/student/assignment/subject', [StudentAssignmentController::class, 'assignmentSubject'])->name('student.assignments.subject');
// Route::get('/student/assignments', [StudentAssignmentController::class, 'assignments'])->name('student.assignments');

Route::get('/student/assignment/subject', [StudentAssignmentController::class, 'assignmentSubject'])->name('student.assignments.subject');
Route::get('/student/assignments', [StudentAssignmentController::class, 'assignments'])->name('student.assignments');
Route::get('/student/exam-results', [StudentExamResultController::class, 'examResultView'])->name('student.exam.results');
Route::get('/student/profile', [StudentProfileController::class, 'profileView'])->name('student.profile');




Route::get('/student/courses-assignment', [CourceViewController::class, 'studentCourses'])->name('student.courses.assignment');
Route::get('/student/course/{id}/subjects-show', [StudentAssignmentController::class, 'showCourseSubjects'])->name('student.course.subjects');
Route::get('/student/course/{id}/subjects-content', [CourceViewController::class, 'showCourseSubjects'])->name('student.course.content');
Route::get('/student/course/{schedule_id}/subjects/{subject_id}/assignment', [StudentAssignmentController::class, 'getAssignment'])->name('student.course.subjects.assignment');
Route::post('/student/course/assignment/submit', [StudentAssignmentController::class, 'submitAssignment'])->name('assignment.submit');
