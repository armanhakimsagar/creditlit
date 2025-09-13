<?php

use Illuminate\Support\Facades\Route;


Route::group(['module' => 'Announcement', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {

	include('holiday.php');
	include('weekend.php');
	
});