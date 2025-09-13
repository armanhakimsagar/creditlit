<?php

use Illuminate\Support\Facades\Route;
Route::group(['module' => 'User', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {
	include('role.php');
	include('permission.php');
	include('user.php');
});
