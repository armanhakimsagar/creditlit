<?php
use Illuminate\Support\Facades\Route;


Route::get('add-pricing','ItemController@addPricing')->name('add.price')->middleware('can.user:pricing.create');
Route::post('pricing-store','ItemController@pricingStore')->name('price.store');


// Late Fine route
Route::get('generate-late-fine','ItemController@generateLateFine')->name('generate.late.fine');
Route::patch('late-fine-store','ItemController@lateFineStore')->name('late.fine.store');
