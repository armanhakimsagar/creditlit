<?php

use Illuminate\Support\Facades\Route;


Route::group(['module' => 'Item', 'middleware' => ['web','redirect_if_logout','adminmiddleware']], function() {
	include('category.php');
	include('item.php');
	include('monthlyItem.php');
	include('pricing.php');
	include('customer.php');
});
