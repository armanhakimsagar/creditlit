<?php

use Illuminate\Support\Facades\Route;
Route::group(['module' => 'Report', 'middleware' => ['web', 'redirect_if_logout', 'adminmiddleware']], function () {
// include Report route

// Order report route
Route::get('order-report', 'ReportController@orderReport')->name('order.report');
Route::post('order-report-filter', 'ReportController@orderReportFilter')->name('order.report.filter');



// Order report route
Route::get('search-company', 'ReportController@searchCompany')->name('search.company');
Route::get('generate-company', 'ReportController@generateCompany')->name('generate.company');
Route::get('generate-company-order', 'ReportController@generateCompanyOrder')->name('generate.company.order');
Route::post('search-company-submit', 'ReportController@searchCompanySubmit')->name('search.company.submit');
Route::get('download-report', 'ReportController@downloadReport')->name('download.report');


// Expense report route
Route::get('expense-report-index', 'ReportController@expenseReportIndex')->name('expense.report.index');
Route::post('expense-report-filter', 'ReportController@expenseReportFilter')->name('expense.report.filter');


// Cash Bank report route
Route::get('cash-book-report-index', 'ReportController@cashBookReportIndex')->name('cash.book.report.index');
Route::post('cash-book-report-filter', 'ReportController@cashBookReportFilter')->name('cash.book.report.filter');

// Cash Bank report route
Route::get('bank-book-report-index', 'ReportController@bankBookReportIndex')->name('bank.book.report.index');
Route::post('bank-book-report-filter', 'ReportController@bankBookReportFilter')->name('bank.book.report.filter');

//Admission Collection Report
Route::get('admission-collection-report', 'ReportController@admissionCollectionReport')->name('admission.collection.report');
Route::post('admission-collection-filter', 'ReportController@collectionReportFilter')->name('admission.collection.filter');

//Student Collection Report
Route::get('student-collection-report', 'ReportController@studentCollectionReport')->name('student.collection.report');
Route::post('student-collection-filter', 'ReportController@studentReportFilter')->name('student.collection.filter');

//SMS Report
Route::get('sms-report', 'ReportController@smsReport')->name('sms.report')->middleware('can.user:sms.report');
Route::post('sms-report-filter', 'ReportController@smsReportFilter')->name('sms.report.filter');

// Income & Expense Report
Route::get('income-report-index', 'incomeReportController@incomeReportIndex')->name('income.report.index');
Route::post('income-report-filter', 'incomeReportController@incomeReportFilter')->name('income.report.filter');

Route::post('get-student-details', 'ReportController@getStudents');
Route::post('get-employee', 'ReportController@getEmployee');


// for account clearence
Route::get('account-clearence-index', 'ReportController@accountClearenceIndex')->name('account.clearence.index');
Route::post('account-clearence-generate', 'ReportController@accountClearenceGenerate')->name('account.clearence.generate');

Route::get('due-report', 'ReportController@dueReport')->name('due.report');
Route::post('due--filter', 'ReportController@dueReportFilter')->name('due.report.filter');

//Salary Item Report
Route::get('salary-item-report', 'ReportController@salaryItemReport')->name('salary.item.report');
Route::post('salary-item-filter', 'ReportController@salaryItemReportFilter')->name('salary.item.filter');
});
