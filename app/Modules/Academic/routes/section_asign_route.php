<?php
use Illuminate\Support\Facades\Route;

//    Section Route

// Route::get('section-asign', 'SectionAsignController@index')->name('section.asign.index');
Route::get('section-asign-create', 'SectionAsignController@create')->name('section.asign.create')->middleware('can.user:section.asign.create');
Route::post('section-asign-store', 'SectionAsignController@store')->name('section.asign.store')->middleware('can.user:section.asign.create');
