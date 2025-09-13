<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'IdCard', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {
	include('idCard.php');
});
