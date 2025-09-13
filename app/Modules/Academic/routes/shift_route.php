
<?php
use Illuminate\Support\Facades\Route;

//Shift Route
Route::get('shift', 'ShiftController@index')->name('shift.index')->middleware('can.user:shift.index');
Route::get('shift-create', 'ShiftController@create')->name('shift.create')->middleware('can.user:shift.create');
Route::post('shift-store', 'ShiftController@store')->name('shift.store')->middleware('can.user:shift.create');
Route::get('shift-edit/{id}', 'ShiftController@edit')->name('shift.edit')->middleware('can.user:shift.edit');
Route::patch('shift-update/{id}', 'ShiftController@update')->name('shift.update')->middleware('can.user:shift.edit');
Route::get('shift-delete/{id}', 'ShiftController@destroy')->name('shift.delete')->middleware('can.user:shift.delete');
Route::get('/shift-trash', 'ShiftController@trash')->name('shift.trash')->middleware('can.user:shift.delete');
// Restore Class
Route::get('/shift-restore/{id}', 'ShiftController@shift_restore')->name('shift.restore')->middleware('can.user:shift.delete');
