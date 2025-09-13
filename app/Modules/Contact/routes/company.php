<?php
use Illuminate\Support\Facades\Route;
// Company CRUD
Route::get('/company-create', 'companyController@create')->name('company.create');
Route::post('/company-store', 'companyController@store')->name('company.store');
Route::get('/company', 'companyController@index')->name('company.index');
Route::get('/company-edit/{id}', 'companyController@edit')->name('company.edit');
Route::patch('/company-update/{id}', 'companyController@update')->name('company.update');
Route::get('selected-company-order', 'companyController@selectedCompanyOrder')->name('selected.company.order');
Route::get('selected-company-order-filter', 'companyController@selectedCompanyOrderFilter')->name('selected.company.order.filter');
Route::get('/company-delete/{id}', 'companyController@destroy')->name('company.delete');
Route::get('/company-trash', 'companyController@trash')->name('company.trash');
// Restore company
Route::get('/company-restore/{id}', 'companyController@companyRestore')->name('company.restore');

// Company Wise pricing
Route::get('add-company-pricing/{id}','companyController@addCompanyPricing')->name('add.company.price');
Route::patch('company-pricing-store/{id}','companyController@companyPricingStore')->name('company.price.store');