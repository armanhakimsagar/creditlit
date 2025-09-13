<?php

use Illuminate\Support\Facades\Route;
//Invoice Report
Route::get('create-invoice', 'InvoiceController@createInvoice')->name('create.invoice');
Route::post('store-invoice', 'InvoiceController@storeInvoice')->name('store.invoice');
Route::get('index-invoice', 'InvoiceController@indexInvoice')->name('index.invoice');
Route::get('/invoice-details/{id}', 'InvoiceController@invoiceDetails')->name('invoice.details');
Route::post('/update-invoice/{id}', 'InvoiceController@updateInvoice')->name('update.invoice');
Route::post('create-invoice-filter', 'InvoiceController@createInvoiceFilter')->name('create.invoice.filter');
Route::post('/get-invoice-customer', 'InvoiceController@getInvoiceCustomer');
