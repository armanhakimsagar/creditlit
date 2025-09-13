<?php
use Illuminate\Support\Facades\Route;

// Class CRUD
Route::get('/student-re-admission', 'ReAdmissionController@index')->name('readmission.index')->middleware('can.user:readmission.index');
Route::post('/student-re-admission-create', 'ReAdmissionController@create')->name('readmission.create')->middleware('can.user:readmission.create');
Route::get('/student-readmission/{classId}/{studentId}', 'ReAdmissionController@singleReadmission')->name('student.readmission')->middleware('can.user:readmission.create');
Route::post('/student-readmission-create', 'ReAdmissionController@singleReadmissionCreate')->name('student.readmission.create');
Route::post('/get-class-list', 'ReAdmissionController@getClassList');