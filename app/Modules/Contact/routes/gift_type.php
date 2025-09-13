<?php

use Illuminate\Support\Facades\Route;

//Gift Type Route
Route::get('gift-type', 'GiftTypeController@index')->name('gift.type.index');
Route::get('gift-type-create', 'GiftTypeController@create')->name('gift.type.create');
Route::post('gift-type-store', 'GiftTypeController@store')->name('gift.type.store');
Route::get('gift-type-edit/{id}', 'GiftTypeController@edit')->name('gift.type.edit');
Route::patch('gift-type-update/{id}', 'GiftTypeController@update')->name('gift.type.update');
Route::get('gift-type-delete/{id}', 'GiftTypeController@destroy')->name('gift.type.delete');
