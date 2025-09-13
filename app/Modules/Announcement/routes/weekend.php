<?php
use Illuminate\Support\Facades\Route;

// Weekend CRUD
Route::get('/weekend-create', 'WeekendController@create')->name('weekend.create')->middleware('can.user:weekend.create');
Route::post('/weekend-store', 'WeekendController@store')->name('weekend.store')->middleware('can.user:weekend.create');
