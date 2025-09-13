<?php

use Illuminate\Support\Facades\Route;


Route::group(['module' => 'Contact', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {
	include('keyPersonnel.php');
	include('bank.php');
	include('branch.php');
	include('company.php');
	include('supplier.php');
	include('gift.php');
	include('gift_type.php');
});

