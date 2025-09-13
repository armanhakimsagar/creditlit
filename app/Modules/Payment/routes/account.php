<?php
use Illuminate\Support\Facades\Route;

 //Account
 Route::get('account-index', 'AccountController@accountIndex')->name('account.index')->middleware('can.user:account.index');
 Route::get('account-create', 'AccountController@accountCreate')->name('account.create')->middleware('can.user:account.create');
 Route::post('account-store','AccountController@accountStore')->name('account.store')->middleware('can.user:account.create');
 Route::get('account-edit/{id}', 'AccountController@accountEdit')->name('account.edit')->middleware('can.user:account.edit');
 Route::patch('account-update/{id}','AccountController@accountUpdate')->name('account.update')->middleware('can.user:account.edit');
 Route::get('account-delete/{id}', 'AccountController@accountDestroy')->name('account.delete')->middleware('can.user:account.delete');
 // get account category
 Route::post('get-account-category', 'AccountController@getAccountCategory');

?>