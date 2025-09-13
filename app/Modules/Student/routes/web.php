<?php

use Illuminate\Support\Facades\Route;

Route::get('academic', 'AcademicController@welcome');
Route::group(['module' => 'Academic', 'middleware' => ['web', 'redirect_if_logout', 'adminmiddleware']], function () {
// include student route
    include ('student.php');
    include ('reAdmission.php');
    include ('admissionReport.php');
});
