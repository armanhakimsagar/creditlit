<?php
use Illuminate\Support\Facades\Route;
        Route::get('monthly-item-index', 'MonthlyItemSetupController@index')->name('monthly.item.index')->middleware('can.user:monthly.item.index');
        Route::get('monthly-item-create', 'MonthlyItemSetupController@create')->name('monthly.item.create')->middleware('can.user:monthly.item.create');
        Route::post('monthly-item-store', 'MonthlyItemSetupController@store')->name('store.monthly.item')->middleware('can.user:monthly.item.create');
        Route::get('monthly-item-setup-index', 'MonthlyItemSetupController@monthlyItemIndex')->name('monthly.item.setup.index')->middleware('can.user:monthly.item.setup.index');
        Route::post('monthly-item-setup-store', 'MonthlyItemSetupController@storeItemSetup')->name('store.monthly.item.setup')->middleware('can.user:monthly.item.setup.index');
        // Route::get('delete-item/{id}', 'ItemController@destroy')->name('delete.item');
        // Route::patch('update-item/{id}', 'ItemController@update')->name('update.item');
