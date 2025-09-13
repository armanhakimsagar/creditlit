<?php
use Illuminate\Support\Facades\Route;

// Holiday CRUD
Route::get('/holiday-create', 'HolidayController@create')->name('holiday.create')->middleware('can.user:holiday.create');
Route::post('/holiday-store', 'HolidayController@store')->name('holiday.store')->middleware('can.user:holiday.create');
Route::get('/holiday', 'HolidayController@index')->name('holiday.index')->middleware('can.user:user.index');
Route::get('/holiday-edit/{id}', 'HolidayController@edit')->name('holiday.edit')->middleware('can.user:holiday.edit');
Route::patch('/holiday-update/{id}', 'HolidayController@update')->name('holiday.update')->middleware('can.user:holiday.edit');
Route::get('/holiday-destroy/{id}', 'HolidayController@destroy')->name('holiday.delete')->middleware('can.user:holiday.delete');
Route::get('/holiday-trash', 'HolidayController@trash')->name('holiday.trash')->middleware('can.user:holiday.create');
// Restore holiday
Route::get('/holiday-restore/{id}', 'HolidayController@holiday_restore')->name('holiday.restore')->middleware('can.user:holiday.create');
