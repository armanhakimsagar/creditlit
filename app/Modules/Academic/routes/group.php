<?php
use Illuminate\Support\Facades\Route;

//Group Route
Route::get('group-index', 'GroupController@index')->name('group.index')->middleware('can.user:group.index');
Route::get('group-create', 'GroupController@create')->name('group.create')->middleware('can.user:group.create');
Route::post('group-store', 'GroupController@store')->name('group.store')->middleware('can.user:group.create');
Route::get('group-edit/{id}', 'GroupController@edit')->name('group.edit')->middleware('can.user:group.edit');
Route::get('group-delete/{id}', 'GroupController@delete')->name('group.delete')->middleware('can.user:group.delete');
Route::patch('group-update/{id}', 'GroupController@update')->name('group.update')->middleware('can.user:group.edit');
Route::get('/group-trash', 'GroupController@trash')->name('group.trash')->middleware('can.user:group.delete');
// Restore Group
Route::get('/group-restore/{id}', 'GroupController@group_restore')->name('group.restore')->middleware('can.user:group.delete');