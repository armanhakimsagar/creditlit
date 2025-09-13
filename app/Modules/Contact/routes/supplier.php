<?php
use Illuminate\Support\Facades\Route;
// Supplier CRUD
Route::get('/supplier-create', 'SupplierController@create')->name('supplier.create');
Route::post('/supplier-store', 'SupplierController@store')->name('supplier.store');
Route::get('/supplier', 'SupplierController@index')->name('supplier.index');
Route::get('/supplier-edit/{id}', 'SupplierController@edit')->name('supplier.edit');
Route::patch('/supplier-update/{id}', 'SupplierController@update')->name('supplier.update');
Route::get('selected-supplier-order', 'SupplierController@selectedSupplierOrder')->name('selected.supplier.order');
Route::get('selected-supplier-order-filter', 'SupplierController@selectedSupplierOrderFilter')->name('selected.supplier.order.filter');
Route::get('/supplier-delete/{id}', 'SupplierController@destroy')->name('supplier.delete');
Route::get('/supplier-trash', 'SupplierController@trash')->name('supplier.trash');
// Restore Supplier
Route::get('/supplier-restore/{id}', 'SupplierController@supplierRestore')->name('supplier.restore');

// Supplier Wise pricing
Route::get('add-supplier-pricing/{id}','SupplierController@addSupplierPricing')->name('add.supplier.price');
Route::patch('supplier-pricing-store/{id}','SupplierController@supplierPricingStore')->name('supplier.price.store');