<?php

use Illuminate\Support\Facades\Route;
// order CRUD
Route::get('/order-create', 'OrderController@create')->name('order.create');
Route::post('/order-store', 'OrderController@store')->name('order.store');
Route::get('/all-order', 'OrderController@allOrder')->name('all.order');
Route::get('/pending-order', 'OrderController@pendingOrder')->name('pending.order');
Route::get('/processing-order', 'OrderController@processingOrder')->name('processing.order');
Route::get('/queried-order', 'OrderController@queryOrder')->name('query.order');
Route::get('/completed-order', 'OrderController@completedOrder')->name('completed.order');
Route::get('/delivered-order', 'OrderController@deliveredOrder')->name('delivered.order');
Route::get('/cancel-order', 'OrderController@cancelOrder')->name('cancel.order');
Route::get('/order-edit/{id}', 'OrderController@edit')->name('order.edit');
Route::patch('/order-update/{id}', 'OrderController@update')->name('order.update');
Route::get('/order-delete/{id}', 'OrderController@destroy')->name('order.delete');
Route::get('/order-details/{id}', 'OrderController@orderDetails')->name('order.details');
Route::patch('/order-status-update/{id}', 'OrderController@orderStatusUpdate')->name('order.status.update');
Route::patch('/order-attachment-update/{id}', 'OrderController@orderAttachmentUpdate')->name('order.attachment.update');
Route::post('/get-bank-type', 'OrderController@getBankType');
Route::post('/get-bank', 'OrderController@getBank');
Route::post('/get-customer', 'OrderController@getCustomer');
Route::post('/get-customer-independent', 'OrderController@getCustomerIndependent');
Route::post('/get-order-keypersonnel', 'OrderController@getOrderKeypersonnel');


//for supplier wise price
Route::get('/get-supplier-wise-price/{supplierId}/{countryId}', 'OrderController@getSupplierWisePrice');

//for Customer wise price
Route::get('/get-customer-wise-price/{customerId}/{countryId}', 'OrderController@getCustomerWisePrice');