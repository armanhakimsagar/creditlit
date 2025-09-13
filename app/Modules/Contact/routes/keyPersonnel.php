<?php
use Illuminate\Support\Facades\Route;
// keypersonnel CRUD
Route::get('/keypersonnel-create', 'keyPersonnelController@create')->name('keypersonnel.create');
Route::post('/keypersonnel-store', 'keyPersonnelController@store')->name('keypersonnel.store');
Route::get('/keypersonnel', 'keyPersonnelController@index')->name('keypersonnel.index');
Route::get('/keypersonnel-edit/{id}', 'keyPersonnelController@edit')->name('keypersonnel.edit');
Route::patch('/keypersonnel-update/{id}', 'keyPersonnelController@update')->name('keypersonnel.update');
Route::get('selected-keypersonnel-order', 'keyPersonnelController@selectedKeypersonnelOrder')->name('selected.keypersonnel.order');
Route::get('selected-keypersonnel-order-filter', 'keyPersonnelController@selectedKeypersonnelOrderFilter')->name('selected.keypersonnel.order.filter');
Route::get('selected-keypersonnel-gift', 'keyPersonnelController@selectedKeypersonnelGift')->name('selected.keypersonnel.gift');
Route::get('selected-keypersonnel-gift-filter', 'keyPersonnelController@selectedKeypersonnelGiftFilter')->name('selected.keypersonnel.gift.filter');
Route::get('/keypersonnel-delete/{id}', 'keyPersonnelController@destroy')->name('keypersonnel.delete');
Route::get('/keypersonnel-trash', 'keyPersonnelController@trash')->name('keypersonnel.trash');
// Restore keypersonnel
Route::get('/keypersonnel-restore/{id}', 'keyPersonnelController@keyPersonnelRestore')->name('keypersonnel.restore');

