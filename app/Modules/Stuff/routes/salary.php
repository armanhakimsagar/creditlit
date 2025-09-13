
<?php
use Illuminate\Support\Facades\Route;
//Salary Item Route
Route::get('salary-item-index', 'SalaryController@salaryItemIndex')->name('salary.item.index')->middleware('can.user:salary.item.index');
Route::get('salary-item-create', 'SalaryController@salaryItemCreate')->name('salary.item.create')->middleware('can.user:salary.item.create');
Route::post('salary-item-store', 'SalaryController@salaryItemStore')->name('salary.item.store')->middleware('can.user:salary.item.create');
Route::get('salary-item-edit/{id}', 'SalaryController@salaryItemEdit')->name('salary.item.edit')->middleware('can.user:salary.item.edit');
Route::patch('salary-item-update/{id}', 'SalaryController@salaryItemUpdate')->name('salary.item.update')->middleware('can.user:salary.item.edit');
Route::get('salary-item-delete/{id}', 'SalaryController@salaryItemDestroy')->name('salary.item.delete')->middleware('can.user:salary.item.delete');


?>

