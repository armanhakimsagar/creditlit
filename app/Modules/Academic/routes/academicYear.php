<?php
use Illuminate\Support\Facades\Route;

//Academic Year Route
Route::get('academic-year-index', 'AcademicYearController@index')->name('academic.year.index')->middleware('can.user:academic.year.index');
Route::get('academic-year-create', 'AcademicYearController@create')->name('academic.year.create')->middleware('can.user:academic.year.index');
Route::post('academic-year-store', 'AcademicYearController@store')->name('academic.year.store')->middleware('can.user:academic.year.index');
Route::get('academic-year-edit/{id}', 'AcademicYearController@edit')->name('academic.year.edit')->middleware('can.user:academic.year.index');
Route::get('academic-year-delete/{id}', 'AcademicYearController@delete')->name('academic.year.delete')->middleware('can.user:academic.year.index');
Route::patch('academic-year-update/{id}', 'AcademicYearController@update')->name('academic.year.update')->middleware('can.user:academic.year.index');
Route::get('/academic-year-trash', 'AcademicYearController@trash')->name('academic.year.trash')->middleware('can.user:academic.year.index');
// Restore Class
Route::get('/academic-year-restore/{id}', 'AcademicYearController@academic_year_restore')->name('academic.year.restore')->middleware('can.user:academic.year.index');
