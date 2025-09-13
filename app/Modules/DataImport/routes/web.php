<?php

use Illuminate\Support\Facades\Route;


Route::group(['module' => 'DataImport', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {

	include('dataImport.php');
	include('attendanceReport.php');
	
});