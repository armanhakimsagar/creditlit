<?php
use Illuminate\Support\Facades\Route;

// Payment
Route::get('receive-payment/{id}', 'PaymentController@receive_payment')->name('receive.payment')->middleware('can.user:student.payment');
Route::get('payment-receipt/{sales_id}', 'PaymentController@payment_receipt')->name('payment.receipt');
Route::post('student-id-details', 'PaymentController@getstudent')->name('student.id.details');
Route::post('get-student-details-and-items', 'PaymentController@getstudentDetails')->name('student.details.and.items');
Route::post('get-item-details', 'PaymentController@itemDetails')->name('get.item.details');
Route::post('payment-store', 'PaymentController@payment_store')->name('payment.store');
Route::post('get-student-monthly-due', 'PaymentController@getMonthlyDue')->name('student.monthly.due');

Route::get('/students-quick-payment/{id}', 'PaymentController@receive_payment')->name('students.quick.payment')->middleware('can.user:student.payment');
Route::post('check-student-exists', 'PaymentController@checkStudentExists')->name('checkStudentExists');

//all payment
Route::get('payment-list', 'PaymentController@paymentList')->name('payment.list')->middleware('can.user:payment.list');
Route::get('payment-edit/{id}', 'PaymentController@paymentEdit')->name('payment.edit')->middleware('can.user:payment.list.edit');
Route::post('payment-item-update/{id}', 'PaymentController@paymentItemUpdate')->name('payment.item.update')->middleware('can.user:payment.list.edit');

//all due
Route::get('due-list', 'PaymentController@dueList')->name('due.list')->middleware('can.user:due.list');
Route::get('due-details/{id}', 'PaymentController@dueDetails')->name('due.details')->middleware('can.user:due.list');
Route::get('due-details/due-item/edit/{id}', 'PaymentController@dueItemEdit');
Route::post('due-item-update', 'PaymentController@dueItemUpdate')->name('due.item.update')->middleware('can.user:due.list.edit');
Route::get('due-item/delete/{id}', 'PaymentController@dueItemDelete')->name('due.item.delete')->middleware('can.user:due.list.delete');

//to get student contact
Route::post('/get-studentscontact', 'PaymentController@getStudentsContact');
Route::post('/get-invoice', 'PaymentController@getInvoice');

// Student wise payment history report
Route::get('/students-wise-payment-history-report/{id}', 'PaymentController@studentWisePaymentHistoryReport')->name('students.wise.payment.history.report');
Route::post('/students-wise-payment-history-report-search', 'PaymentController@studentWisePaymentHistoryReportSearch')->name('students.wise.payment.history.report.search');

Route::get('/data-correction', 'PaymentController@dataCorrection');



