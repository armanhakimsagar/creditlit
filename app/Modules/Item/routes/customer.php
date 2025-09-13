<?php
use Illuminate\Support\Facades\Route;

// Customer CRUD
Route::get('/customer-create', 'CustomerController@create')->name('customer.create');
Route::post('/customer-store', 'CustomerController@store')->name('customer.store');
Route::get('/customer', 'CustomerController@index')->name('customer.index');
Route::get('/customer-edit/{id}', 'CustomerController@edit')->name('customer.edit');
Route::patch('/customer-update/{id}', 'CustomerController@update')->name('customer.update');
Route::get('/customer-destroy/{id}', 'CustomerController@destroy')->name('customer.delete');
Route::get('/customer-profile/{id}', 'CustomerController@customerProfile')->name('customer.profile');
Route::get('/customer-profile/payment-history/{id}', 'CustomerController@customerPaymentHistory')->name('customer.payment.history');
// Receive Customer Payment
Route::get('receive-customer-payment/{id}', 'CustomerController@receive_customer_payment')->name('receive.customer.payment')->middleware('can.user:customer.payment');
Route::post('get-customer-details-and-items', 'CustomerController@getCustomerDetails')->name('customer.details.and.items');
Route::post('customer-payment-store', 'CustomerController@customer_payment_store')->name('customer.payment.store');
Route::get('customer-payment-receipt/{sales_id}', 'CustomerController@customer_payment_receipt')->name('customer.payment.receipt');