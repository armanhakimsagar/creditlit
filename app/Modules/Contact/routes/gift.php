<?php
use Illuminate\Support\Facades\Route;
// Gift CRUD
Route::get('/gift-create', 'GiftController@create')->name('gift.create');
Route::post('/gift-store', 'GiftController@store')->name('gift.store');
Route::get('/gift', 'GiftController@index')->name('gift.index');
Route::get('/gift-edit/{id}', 'GiftController@edit')->name('gift.edit');
Route::patch('/gift-update/{id}', 'GiftController@update')->name('gift.update');
Route::get('/gift-delete/{id}', 'GiftController@destroy')->name('gift.delete');
