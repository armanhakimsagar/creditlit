<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'invoice', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {
	include('invoice.php');
});
