<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Mark', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {

	include('markAttribute.php');
	include('markGrade.php');
	
});