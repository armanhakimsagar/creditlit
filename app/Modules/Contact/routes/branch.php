<?php

use Illuminate\Support\Facades\Route;
// branch CRUD
Route::get('/branch-create', 'branchController@create')->name('branch.create');
Route::post('/branch-store', 'branchController@store')->name('branch.store');
Route::get('/branch', 'branchController@index')->name('branch.index');
Route::get('/branch-edit/{id}', 'branchController@edit')->name('branch.edit');
Route::patch('/branch-update/{id}', 'branchController@update')->name('branch.update');
Route::get('selected-branch-order', 'branchController@selectedBranchOrder')->name('selected.branch.order');
Route::get('selected-branch-order-filter', 'branchController@selectedBranchOrderFilter')->name('selected.branch.order.filter');
Route::get('/branch-delete/{id}', 'branchController@destroy')->name('branch.delete');
Route::get('/branch-trash', 'branchController@trash')->name('branch.trash');
Route::post('/get-bank-type', 'branchController@getBankType');
// Restore branch
Route::get('/branch-restore/{id}','branchController@branchRestore')->name('branch.restore');

// Branch Wise pricing
Route::get('add-branch-pricing/{id}','branchController@addBranchPricing')->name('add.branch.price');
Route::patch('branch-pricing-store/{id}','branchController@branchPricingStore')->name('branch.price.store');