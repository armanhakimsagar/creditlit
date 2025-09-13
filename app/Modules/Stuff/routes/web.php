<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Stuff', 'middleware' => ['web', 'redirect_if_logout', 'adminmiddleware']], function () {
    include('stuff.php');
    include('employePayroll.php');
	include('department.php');
	include('designation.php');
	include('salary.php');
});
