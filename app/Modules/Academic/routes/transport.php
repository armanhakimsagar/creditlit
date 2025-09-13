<?php
use Illuminate\Support\Facades\Route;

//Transport Route
Route::get('transport-index', 'TransportController@index')->name('transport.index')->middleware('can.user:transport.index');
Route::get('transport-create', 'TransportController@create')->name('transport.create')->middleware('can.user:transport.create');
Route::post('transport-store', 'TransportController@store')->name('transport.store')->middleware('can.user:transport.create');
Route::get('transport-edit/{id}', 'TransportController@edit')->name('transport.edit')->middleware('can.user:transport.edit');
Route::get('transport-delete/{id}', 'TransportController@delete')->name('transport.delete')->middleware('can.user:transport.delete');
Route::patch('transport-update/{id}', 'TransportController@update')->name('transport.update')->middleware('can.user:transport.edit');
Route::get('/transport-trash', 'TransportController@trash')->name('transport.trash')->middleware('can.user:transport.delete');
// Restore Class
Route::get('/transport-restore/{id}', 'TransportController@transport_restore')->name('transport.restore')->middleware('can.user:transport.delete');
