<?php
use Illuminate\Support\Facades\Route;

//    Exam-type Route
        Route::get('exam', 'ExamController@index')->name('exam.index')->middleware('can.user:exam.index');
        Route::get('exam-create', 'ExamController@create')->name('exam.create')->middleware('can.user:exam.create');
        Route::post('exam-store', 'ExamController@store')->name('exam.store')->middleware('can.user:exam.create');
        Route::get('exam-edit/{id}', 'ExamController@edit')->name('exam.edit')->middleware('can.user:exam.edit');
        Route::patch('exam-update/{id}', 'ExamController@update')->name('exam.update')->middleware('can.user:exam.edit');
        Route::get('exam-delete/{id}', 'ExamController@destroy')->name('exam.delete')->middleware('can.user:exam.delete');

?>