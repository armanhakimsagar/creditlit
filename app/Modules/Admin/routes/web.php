<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Admin', 'middleware' => ['web']], function() {

	Route::get('/','Auth\LoginController@index');

	Route::get('login','Auth\LoginController@index');

	Route::post('do_login','Auth\LoginController@post_login');

    Route::get('reset/password/', 'Auth\LoginController@resetpassword')->name('reset.password');
    Route::get('reset/customer/password/{slug}','Auth\LoginController@change_form');

    Route::post('customer/save-change','Auth\LoginController@save_chage_password')->name('customer.pass.change');

    Route::post('customer/send/mail','Auth\LoginController@sendmailtouser')->name('customer.resetpassword.sendmail');

	Route::post('logout', 'Auth\LoginController@logout')->name('logout');


});


Route::group(['module' => 'Admin', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {

	Route::get('admin-dashboard', 'AdminController@index');

	Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
	});

	Route::get('/clear-route', function() {
    $exitCode = Artisan::call('route:clear');
    // return what you want
	});

	Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize:clear');
    // return what you want
	});

});
