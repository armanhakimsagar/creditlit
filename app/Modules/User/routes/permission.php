<?php
use Illuminate\Support\Facades\Route;
// PERMISSION CRUD
Route::get('/permission-create', 'PermissionController@create')->name('permission.create')->middleware('can.user:permission.create');
Route::post('/permission-store', 'PermissionController@store')->name('permission.store')->middleware('can.user:permission.create');
Route::get('/permission', 'PermissionController@index')->name('permission.index')->middleware('can.user:permission.index');
Route::get('/permission-edit/{id}', 'PermissionController@edit')->name('permission.edit')->middleware('can.user:permission.edit');
Route::patch('/permission-update/{id}', 'PermissionController@update')->name('permission.update')->middleware('can.user:permission.edit');
// Route::get('/permission-destroy/{id}', 'PermissionController@destroy')->name('permission.delete');