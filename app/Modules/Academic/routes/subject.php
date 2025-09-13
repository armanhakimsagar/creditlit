<?php
use Illuminate\Support\Facades\Route;

//Subject CRUD
Route::get('/subject-create', 'SubjectController@create')->name('subject.create')->middleware('can.user:subject.create');
Route::post('/subject-store', 'SubjectController@store')->name('subject.store')->middleware('can.user:subject.create');
Route::get('/subject', 'SubjectController@index')->name('subject.index')->middleware('can.user:subject.index');
Route::get('/subject-edit/{id}', 'SubjectController@edit')->name('subject.edit')->middleware('can.user:subject.edit');
Route::patch('/subject-update/{id}', 'SubjectController@update')->name('subject.update')->middleware('can.user:subject.edit');
Route::get('/subject-destroy/{id}', 'SubjectController@destroy')->name('subject.delete')->middleware('can.user:subject.delete');
Route::get('/subject-trash', 'SubjectController@trash')->name('subject.trash')->middleware('can.user:subject.delete');
// Restore Subject
Route::get('/subject-restore/{id}', 'SubjectController@subject_restore')->name('subject.restore')->middleware('can.user:subject.delete');

// Subject assign to class
Route::get('subject-asign-create', 'SubjectController@assignCreate')->name('subject.assign.create')->middleware('can.user:subject.assign.create');
Route::post('subject-asign-store', 'SubjectController@assignStore')->name('subject.assign.store')->middleware('can.user:subject.assign.create');
