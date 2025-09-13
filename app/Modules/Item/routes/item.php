<?php
use Illuminate\Support\Facades\Route;
Route::get('country-index', 'ItemController@index')->name('item.index')->middleware('can.user:item.index');
Route::get('create-country', 'ItemController@create')->name('create.item')->middleware('can.user:item.create');
Route::post('country-store', 'ItemController@store')->name('store.item')->middleware('can.user:item.create');
Route::get('edit-country/{id}', 'ItemController@edit')->name('edit.item')->middleware('can.user:item.edit');
Route::get('delete-country/{id}', 'ItemController@destroy')->name('delete.item')->middleware('can.user:item.delete');
Route::patch('update-country/{id}', 'ItemController@update')->name('update.item')->middleware('can.user:item.edit');
