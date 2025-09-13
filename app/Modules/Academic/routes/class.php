<?php
use Illuminate\Support\Facades\Route;

// Class CRUD
Route::get('/class-create', 'ClassController@create')->name('class.create')->middleware('can.user:class.create');
Route::post('/class-store', 'ClassController@store')->name('class.store')->middleware('can.user:class.create');
Route::get('/class', 'ClassController@index')->name('class.index')->middleware('can.user:class.index');
Route::get('/class-edit/{id}', 'ClassController@edit')->name('class.edit')->middleware('can.user:class.edit');
Route::patch('/class-update/{id}', 'ClassController@update')->name('class.update')->middleware('can.user:class.edit');
Route::get('/class-destroy/{id}', 'ClassController@destroy')->name('class.delete')->middleware('can.user:class.delete');
Route::get('/class-trash', 'ClassController@trash')->name('class.trash')->middleware('can.user:class.delete');
// Restore Class
Route::get('/class-restore/{id}', 'ClassController@class_restore')->name('class.restore')->middleware('can.user:class.delete');
