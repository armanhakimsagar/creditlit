<?php
use Illuminate\Support\Facades\Route;

// Designation CRUD
Route::get('/designation-create', 'DesignationController@create')->name('designation.create')->middleware('can.user:designation.create');
Route::post('/designation-store', 'DesignationController@store')->name('designation.store')->middleware('can.user:designation.create');
Route::get('/designation', 'DesignationController@index')->name('designation.index')->middleware('can.user:designation.index');
Route::get('/designation-edit/{id}', 'DesignationController@edit')->name('designation.edit')->middleware('can.user:designation.edit');
Route::patch('/designation-update/{id}', 'DesignationController@update')->name('designation.update')->middleware('can.user:designation.edit');
Route::get('/designation-destroy/{id}', 'DesignationController@destroy')->name('designation.delete')->middleware('can.user:designation.delete');
Route::get('/designation-trash', 'DesignationController@trash')->name('designation.trash')->middleware('can.user:designation.index');
// Restore designation
Route::get('/designation-restore/{id}', 'DesignationController@designation_restore')->name('designation.restore')->middleware('can.user:designation.create');
