<?php
use Illuminate\Support\Facades\Route;

//Exam-type Route
Route::get('exam-type', 'ExamTypeController@index')->name('exam_type.index')->middleware('can.user:exam_type.index');
Route::get('exam-type-create', 'ExamTypeController@create')->name('exam_type.create')->middleware('can.user:exam_type.create');
Route::post('exam-type-store', 'ExamTypeController@store')->name('exam_type.store')->middleware('can.user:exam_type.create');
Route::get('exam-type-edit/{id}', 'ExamTypeController@edit')->name('exam_type.edit')->middleware('can.user:exam_type.edit');
Route::patch('exam-type-update/{id}', 'ExamTypeController@update')->name('exam_type.update')->middleware('can.user:exam_type.edit');
Route::get('exam-type-delete/{id}', 'ExamTypeController@destroy')->name('exam_type.delete')->middleware('can.user:exam_type.delete');
Route::get('exam-type-trash', 'ExamTypeController@trash')->name('exam_type.trash')->middleware('can.user:exam_type.index');
// Restore Class
Route::get('exam-type-restore/{id}', 'ExamTypeController@exam_type_restore')->name('exam_type.restore')->middleware('can.user:exam_type.delete');
