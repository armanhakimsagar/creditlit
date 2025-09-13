<?php
use Illuminate\Support\Facades\Route;

//Account Category
Route::get('account-category-index', 'AccountController@Index')->name('account.category.index')->middleware('can.user:account.category.index');
Route::get('account-category-create', 'AccountController@create')->name('account.category.create')->middleware('can.user:account.category.create');
Route::post('account-category-store', 'AccountController@store')->name('account.category.store')->middleware('can.user:account.category.create');
Route::get('account-category-edit/{id}', 'AccountController@edit')->name('account.category.edit')->middleware('can.user:account.category.edit');
Route::patch('account-category-update/{id}', 'AccountController@update')->name('account.category.update')->middleware('can.user:account.category.edit');
Route::get('account-category-delete/{id}', 'AccountController@destroy')->name('account.category.delete')->middleware('can.user:account.category.delete');
Route::get('/account-category-trash', 'AccountController@trash')->name('account.category.trash')->middleware('can.user:account.category.create');
//Restore Category
Route::get('/account-category-restore/{id}', 'AccountController@account_category_restore')->name('account.category.restore')->middleware('can.user:account.category.create');
