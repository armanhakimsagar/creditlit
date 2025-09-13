<?php

use Illuminate\Support\Facades\Route;

//All Student ID Card Route
Route::get('all-student-id-card-index', 'IdCardController@allStudentIdCard')->name('all.student.id.card.index')->middleware('can.user:student.id.card');
Route::post('generate-student-id-card', 'IdCardController@generateStudentIdCard')->name('generate.student.id.card');


//All Guardian ID Card Route
Route::get('all-guardian-id-card-index', 'IdCardController@allGuardianIdCard')->name('all.guardian.id.card.index')->middleware('can.user:guardian.id.card');
Route::post('generate-guardian-id-card', 'IdCardController@generateGuardianIdCard')->name('generate.guardian.id.card');

//All Employee ID Card Route
Route::get('all-employee-id-card-index', 'IdCardController@allEmployeeIdCard')->name('all.employee.id.card.index')->middleware('can.user:employee.id.card');
Route::post('generate-employee-id-card', 'IdCardController@generateEmployeeIdCard')->name('generate.employee.id.card');