
<?php
use Illuminate\Support\Facades\Route;

//Student Type Route
Route::get('student-type', 'StudentTypeController@index')->name('student.type.index');
Route::get('student-type-create', 'StudentTypeController@create')->name('student.type.create');
Route::post('student-type-store', 'StudentTypeController@store')->name('student.type.store');
Route::get('student-type-edit/{id}', 'StudentTypeController@edit')->name('student.type.edit');
Route::patch('student-type-update/{id}', 'StudentTypeController@update')->name('student.type.update');
Route::get('student-type-delete/{id}', 'StudentTypeController@destroy')->name('student.type.delete');
Route::get('/student-type-trash', 'StudentTypeController@trash')->name('student.type.trash');
// Restore Student Type
Route::get('/student-type-restore/{id}', 'StudentTypeController@studentTypeRestore')->name('student.type.restore');
