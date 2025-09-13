<?php
use Illuminate\Support\Facades\Route;

Route::get('/admission-report-index', 'AdmissionReportController@admissionReportIndex')->name('admission.report.index')->middleware('can.user:admission.report');
Route::post('/admission-report-create', 'AdmissionReportController@admissionReportCreate')->name('admission.report.create');
Route::post('/admission-report-filter', 'AdmissionReportController@admissionReportFilter')->name('admission.report.filter');
