<?php
use Illuminate\Support\Facades\Route;

// Department CRUD
Route::get('/department-create', 'DepartmentController@create')->name('department.create')->middleware('can.user:department.create');
Route::post('/department-store', 'DepartmentController@store')->name('department.store')->middleware('can.user:department.create');
Route::get('/department', 'DepartmentController@index')->name('department.index')->middleware('can.user:department.index');
Route::get('/department-edit/{id}', 'DepartmentController@edit')->name('department.edit')->middleware('can.user:department.edit');
Route::patch('/department-update/{id}', 'DepartmentController@update')->name('department.update')->middleware('can.user:department.edit');
Route::get('/department-destroy/{id}', 'DepartmentController@destroy')->name('department.delete')->middleware('can.user:department.delete');
Route::get('/department-trash', 'DepartmentController@trash')->name('department.trash')->middleware('can.user:department.index');
// Restore Department
Route::get('/department-restore/{id}', 'DepartmentController@department_restore')->name('department.restore')->middleware('can.user:department.create');
