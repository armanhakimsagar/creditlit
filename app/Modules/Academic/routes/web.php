<?php

use Illuminate\Support\Facades\Route;

Route::get('academic', 'AcademicController@welcome');

Route::group(['module' => 'Academic', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {
	include('class.php');
	include('shift_route.php');
	include('version_route.php');
	include('section_route.php');
	include('group.php');
	include ('transport.php');
	include('section_asign_route.php');
    include ('academicYear.php');
    include ('subject.php');
    include ('studentType.php');
});
