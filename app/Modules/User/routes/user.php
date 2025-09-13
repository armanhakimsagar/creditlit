<?php
use Illuminate\Support\Facades\Route;
// User CRUD
Route::get('/user-create', 'UserController@create')->name('user.create')->middleware('can.user:user.create');
Route::post('/user-store', 'UserController@store')->name('user.store')->middleware('can.user:user.create');
Route::get('/user', 'UserController@index')->name('user.index')->middleware('can.user:user.index');
Route::get('/user-edit/{id}', 'UserController@edit')->name('user.edit')->middleware('can.user:user.edit');
Route::get('/user-profile-edit/{id}', 'UserController@profileEdit')->name('user.profile.edit');
Route::patch('/user-update/{id}', 'UserController@update')->name('user.update')->middleware('can.user:user.edit');
Route::get('/user-destroy/{id}', 'UserController@destroy')->name('user.delete')->middleware('can.user:user.delete');
Route::get('/user-password-edit/{id}', 'UserController@userPasswordEdit')->name('user.password.edit');
Route::patch('/user-password-update/{id}', 'UserController@userPasswordUpdate')->name('user.password.update');