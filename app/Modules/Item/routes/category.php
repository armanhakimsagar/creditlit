<?php
use Illuminate\Support\Facades\Route;

//    Version Route
        Route::get('category', 'CategoryController@index')->name('category.index')->middleware('can.user:category.index');
        Route::get('category-create', 'CategoryController@create')->name('category.create')->middleware('can.user:category.create');
        Route::post('category-store', 'CategoryController@store')->name('category.store')->middleware('can.user:category.create');
        Route::get('category-edit/{id}', 'CategoryController@edit')->name('category.edit')->middleware('can.user:category.edit');
        Route::patch('category-update/{id}', 'CategoryController@update')->name('category.update')->middleware('can.user:category.edit');
        Route::get('category-delete/{id}', 'CategoryController@destroy')->name('category.delete')->middleware('can.user:category.delete');

?>