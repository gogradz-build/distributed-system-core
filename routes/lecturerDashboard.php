<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LecturerDashboard\ExamController;

use App\Http\Controllers\LecturerDashboard\SubjectNoteController;
use App\Http\Controllers\LecturerDashboard\LecturerProfileController;
use App\Http\Controllers\LecturerDashboard\LecturerDashboardController;
use App\Http\Controllers\LecturerDashboard\LecturerAssignmentController;

//Auth
Route::get('/lecturer/dashboard', [LecturerDashboardController::class, 'lecturerDashboard'])->name('lecturer.dashboard');
Route::get('/lecturer/course-show', [SubjectNoteController::class, 'showCourses'])->name('lecturer.course.show');
// Route::get('/lecturer/course/subjects-show', [SubjectNoteController::class, 'showSubjects'])->name('lecturer.course.subjects.show');
// Route::get('/lecturer/course/subjects/add-note', [SubjectNoteController::class, 'addNotes'])->name('lecturer.course.subjects.add.notes');

//assignment
Route::get('/lecturer/assignment/courses', [LecturerAssignmentController::class, 'showAssignmentCourses'])->name('lecturer.course.assignment.courses');





//lecture dashboard

Route::get('/lecturer/profile', [SubjectNoteController::class, 'profile'])->name('lecture.profile');
// Route::post('/lecturer/profile/image', [LecturerController::class, 'profileImage'])->name('lecture.profile.image');

//lecture dashboard note
Route::resource('notes', SubjectNoteController::class);
Route::get('/lecturer/courses', [SubjectNoteController::class, 'lecturerCourses'])->name('lecturer.courses');
Route::get('/lecturer/course/{id}/subjects-show', [SubjectNoteController::class, 'showCourseSubjects'])->name('lecturer.course.subjects');
Route::get('/lecturer/course/{schedule_id}/subjects/{subject_id}/add-notes', [SubjectNoteController::class, 'addNotes'])->name('lecturer.course.subjects.add.notes');
Route::post('/lecturer/course/{schedule_id}/subjects/{subject_id}/notes', [SubjectNoteController::class, 'addNotesToSubject'])->name('notes.add.store');


Route::resource('notes', SubjectNoteController::class);

//exam result
Route::get('/lecturer/add-exam-result', [ExamController::class, 'showExam'])->name('lecturer.add.exam.result');

//profile
Route::get('/lecturer/profile', [LecturerProfileController::class, 'LecturerProfile'])->name('lecturer.profile');

//lecture dashboard assignment
Route::resource('assignments', LecturerAssignmentController::class);
Route::get('/lecturer/assignment/{id}/subjects', [LecturerAssignmentController::class, 'showCourseSubjects'])->name('lecturer.course.assignment.subjects');
Route::get('/lecturer/assignment/{schedule_id}/subjects/{subject_id}/panel', [LecturerAssignmentController::class, 'showAssignmentPanel'])->name('lecturer.course.assignment.panel');
Route::post('/lecturer/course/{schedule_id}/subjects/{subject_id}/assignment', [LecturerAssignmentController::class, 'addAssignment'])->name('assignments.add.store');
Route::get('/lecturer/course/{schedule_id}/subjects/{subject_id}/assignment', [LecturerAssignmentController::class, 'getAssignment'])->name('subject.assignments');
Route::get('/lecturer/course/subjects/{assignment_id}/assignment-submissions', [LecturerAssignmentController::class, 'getAssignmentSubmissions'])->name('subject.assignments.submissions');
Route::post('/lecturer/course/assignment', [LecturerAssignmentController::class, 'submitMark'])->name('assignment.submission.marks');




