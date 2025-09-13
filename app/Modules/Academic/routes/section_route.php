
<?php
use Illuminate\Support\Facades\Route;

//Section Route
Route::get('section', 'SectionController@index')->name('section.index')->middleware('can.user:section.index');
Route::get('section-create', 'SectionController@create')->name('section.create')->middleware('can.user:section.create');
Route::post('section-store', 'SectionController@store')->name('section.store')->middleware('can.user:section.create');
Route::get('section-edit/{id}', 'SectionController@edit')->name('section.edit')->middleware('can.user:section.edit');
Route::patch('section-update/{id}', 'SectionController@update')->name('section.update')->middleware('can.user:section.edit');
Route::get('section-delete/{id}', 'SectionController@destroy')->name('section.delete')->middleware('can.user:section.delete');
Route::get('/section-trash', 'SectionController@trash')->name('section.trash')->middleware('can.user:section.delete');
// Restore Class
Route::get('/section-restore/{id}', 'SectionController@section_restore')->name('section.restore')->middleware('can.user:section.delete');