<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'order', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {
	include('order.php');
});

