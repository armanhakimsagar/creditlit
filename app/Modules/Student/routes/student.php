


<?php
use Illuminate\Support\Facades\Route;

// Class CRUD
Route::get('/student-create', 'StudentController@create')->name('student.create')->middleware('can.user:student.create');
Route::post('/student-store', 'StudentController@store')->name('student.store')->middleware('can.user:student.create');
Route::get('/student', 'StudentController@index')->name('student.index')->middleware('can.user:student.index');
Route::get('/student-edit/{id}', 'StudentController@edit')->name('student.edit')->middleware('can.user:student.edit');
Route::get('/student-profile/{id}', 'StudentController@profile')->name('student.profile')->middleware('can.user:student.profile');
Route::patch('/student-update/{id}', 'StudentController@update')->name('student.update')->middleware('can.user:student.edit');
Route::get('/student-destroy/{id}', 'StudentController@destroy')->name('student.delete')->middleware('can.user:student.delete');
Route::post('/get-sections', 'StudentController@getSections');
Route::get('/get-presenst-district/{id}', 'StudentController@getPresentDistrict');
Route::get('/get-presenst-upazila/{id}', 'StudentController@getPresentUpazila');
Route::get('/get-permanent-district/{id}', 'StudentController@getPermanentDistrict');
Route::get('/get-permanent-upazila/{id}', 'StudentController@getPermanentUpazila');
Route::post('/get-students', 'StudentController@getStudents');
Route::get('/student-trash', 'StudentController@trash')->name('student.trash')->middleware('can.user:student.delete');
Route::get('bulk-student-edit', 'StudentController@bulkStudentEdit')->name('bulk.student.edit')->middleware('can.user:bulk.student.edit');
Route::post('bulk-student-update', 'StudentController@bulkStudentUpdate')->name('bulk.student.update');
Route::post('bulk-student-edit-filter', 'StudentController@bulkStudentEditFilter')->name('bulk.student.edit.filter');
// Restore Student
Route::get('/student-restore/{id}', 'StudentController@student_restore')->name('student.restore')->middleware('can.user:student.delete');
// payment History
Route::get('/student-profile/payment-history/{id}', 'StudentController@paymentHistory')->name('payment.history');
Route::get('/student-import-one', 'StudentController@studentImportwith_due')->name('student.import.one');
Route::get('/student-import-two', 'StudentController@studentImportwith_due_two')->name('student.import.two');
Route::get('/student-info', 'StudentController@studentInfo')->name('student.info');

Route::get('update-sales-realtion-table', 'StudentController@updateSalesRel');
Route::get('month-wise-discount-update', 'StudentController@disCountUpdate');
// student Payment Setup
Route::get('/student-payment-setup/{id}', 'StudentController@paymentSetup')->name('students.payment.setup');
Route::get('/student-wise-payment-price-list/{id}', 'StudentController@studentWisePaymentPriceList')->name('students.wise.payment.price.list');
Route::post('/students-wise-payment-price-list-search', 'StudentController@studentWisePaymentPriceListSearch')->name('students.wise.payment.price.list.search');
Route::post('/get-payment-data/{id}', 'StudentController@getPaymentSetup');
Route::post('/payment-setup-store/', 'StudentController@paymentSetupStore')->name('payment.setup.store');
Route::get('/student-payment-setup-update/{id}', 'StudentController@paymentsetupstoreupdate')->name('student.payment.setup');
