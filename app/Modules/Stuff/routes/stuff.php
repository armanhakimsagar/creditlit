
<?php
use Illuminate\Support\Facades\Route;

//    Stuff Route
Route::get('employee-index', 'StuffController@index')->name('employee.index')->middleware('can.user:employee.index');
Route::get('employee-create', 'StuffController@create')->name('employee.create')->middleware('can.user:employee.create');
Route::post('employee-store', 'StuffController@store')->name('employee.store')->middleware('can.user:employee.create');
Route::get('employee-edit/{id}', 'StuffController@edit')->name('employee.edit')->middleware('can.user:employee.edit');
Route::patch('employee-update/{id}', 'StuffController@update')->name('employee.update')->middleware('can.user:employee.edit');
Route::get('employee-delete/{id}', 'StuffController@destroy')->name('employee.delete')->middleware('can.user:employee.delete');
Route::get('employee-entry', 'StuffController@employeeEntry')->name('employee.entry');
Route::get('employee-report', 'StuffController@employeSetupEntry')->name('employee.report');

?>