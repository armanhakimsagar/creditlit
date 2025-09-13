<?php
use Illuminate\Support\Facades\Route;

//Student Mark Generate Route
Route::get('student-mark-generate-create', 'StudentMarkGenerateController@create')->name('student.mark.generate.create');
Route::post('student-mark-generate-store', 'StudentMarkGenerateController@store')->name('student.mark.generate.store');
Route::post('/get-exam', 'StudentMarkGenerateController@getExam');
Route::post('/get-subject', 'StudentMarkGenerateController@getSubject');
Route::get('/mark-destroy/{id}', 'StudentMarkGenerateController@destroy');

//Subject Wise Result Route
Route::get('subject-wise-result', 'StudentMarkGenerateController@subjectWiseResult')->name('subject.wise.result');


//Student Wise Result Route
Route::get('student-wise-result', 'StudentMarkGenerateController@studentWiseResult')->name('student.wise.result');
Route::post('/get-student', 'StudentMarkGenerateController@getStudent');
Route::post('/get-stuedents-mark', 'StudentMarkGenerateController@getStudentMark');


//All Student Result Route
Route::get('all-student-result-index', 'StudentMarkGenerateController@allStudentResultIndex')->name('all.student.result.index');
Route::post('generate-marksheet', 'StudentMarkGenerateController@generateMarksheet')->name('generate.marksheet');
