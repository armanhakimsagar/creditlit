<?php
use Illuminate\Support\Facades\Route;

//Version Route
Route::get('version', 'VersionController@index')->name('version.index')->middleware('can.user:version.index');
Route::get('version-create', 'VersionController@create')->name('version.create')->middleware('can.user:version.create');
Route::post('version-store', 'VersionController@store')->name('version.store')->middleware('can.user:version.create');
Route::get('version-edit/{id}', 'VersionController@edit')->name('version.edit')->middleware('can.user:version.edit');
Route::patch('version-update/{id}', 'VersionController@update')->name('version.update')->middleware('can.user:version.edit');
Route::get('version-delete/{id}', 'VersionController@destroy')->name('version.delete')->middleware('can.user:version.delete');
Route::get('/version-trash', 'VersionController@trash')->name('version.trash')->middleware('can.user:version.delete');
// Restore Class
Route::get('/version-restore/{id}', 'VersionController@version_restore')->name('version.restore')->middleware('can.user:version.delete');