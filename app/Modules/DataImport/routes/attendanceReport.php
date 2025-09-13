<?php
use Illuminate\Support\Facades\Route;

//Attandance Report Route
Route::get('student-daily-attendance', 'AttendanceReport@studentDailyAttendance')->name('student.daily.attendance')->middleware('can.user:student.daily.attendance');
Route::post('student-daily-attendance-filter', 'AttendanceReport@studentDailyAttendanceFilter')->name('student.daily.attendance.filter');
