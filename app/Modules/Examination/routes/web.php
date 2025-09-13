<?php

use Illuminate\Support\Facades\Route;

Route::get('examination', 'ExaminationController@welcome');

Route::group(['module' => 'Academic', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {

	include('exam_type_route.php');
	include('exam_route.php');
	include('studentMarkGenerate.php');
	
});