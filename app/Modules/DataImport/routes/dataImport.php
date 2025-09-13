<?php
use Illuminate\Support\Facades\Route;

//Data-Import Route
Route::get('attendance-data-import-index', 'DataImportController@attendanceDataImportIndex')->name('attendance.data.import.index')->middleware('can.user:attendance.import.index');
Route::post('attendance-data-insert-import', 'DataImportController@attendanceDataInsertImport')->name('attendance.data.insert.import')->middleware('can.user:attendance.import.create');
Route::get('attendance-data-insert-show/{id}', 'DataImportController@attendanceDataInsertShow')->name('attendance.data.insert.show')->middleware('can.user:attendance.import.create');
Route::post('attendance-data-insert-update', 'DataImportController@attendanceDataInsertUpdate')->name('attendance.data.insert.update')->middleware('can.user:attendance.import.edit');
Route::get('attendance-data-insert-import-data/{id}', 'DataImportController@attendanceDataInsertImportData')->name('attendance.data.insert.import.data')->middleware('can.user:attendance.import.create');

