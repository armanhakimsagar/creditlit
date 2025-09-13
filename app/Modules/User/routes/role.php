<?php
use Illuminate\Support\Facades\Route;
// Role CRUD
Route::get('/role-create', 'RoleController@create')->name('role.create')->middleware('can.user:role.create');
Route::post('/role-store', 'RoleController@store')->name('role.store')->middleware('can.user:role.create');
Route::get('/role', 'RoleController@index')->name('role.index')->middleware('can.user:role.index');
Route::get('/role-edit/{id}', 'RoleController@edit')->name('role.edit')->middleware('can.user:role.edit');
Route::patch('/role-update/{id}', 'RoleController@update')->name('role.update')->middleware('can.user:role.edit');
Route::get('/role-destroy/{id}', 'RoleController@destroy')->name('role.delete')->middleware('can.user:role.delete');
Route::get('/role-trash', 'RoleController@trash')->name('role.trash')->middleware('can.user:role.create');
// Restore Class
Route::get('/role-restore/{id}', 'RoleController@role_restore')->name('role.restore')->middleware('can.user:role.create');