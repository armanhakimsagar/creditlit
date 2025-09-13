<?php
use Illuminate\Support\Facades\Route;
// Bank CRUD
Route::get('/bank-create', 'BankController@create')->name('bank.create');
Route::post('/bank-store', 'BankController@store')->name('bank.store');
Route::get('/bank', 'BankController@index')->name('bank.index');
Route::get('/bank-edit/{id}', 'BankController@edit')->name('bank.edit');
Route::patch('/bank-update/{id}', 'BankController@update')->name('bank.update');
Route::get('selected-bank-order', 'BankController@selectedBankOrder')->name('selected.bank.order');
Route::get('selected-bank-order-filter', 'BankController@selectedBankOrderFilter')->name('selected.bank.order.filter');
Route::get('/bank-delete/{id}', 'BankController@destroy')->name('bank.delete');
Route::get('/bank-trash', 'BankController@trash')->name('bank.trash');
// Restore Bank
Route::get('/bank-restore/{id}', 'BankController@bankRestore')->name('bank.restore');

// Bank Wise pricing
Route::get('add-bank-pricing/{id}','BankController@addBankPricing')->name('add.bank.price');
Route::patch('bank-pricing-store/{id}','BankController@bankPricingStore')->name('bank.price.store');