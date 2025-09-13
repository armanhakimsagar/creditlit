<?php

use Illuminate\Support\Facades\Route;

Route::get('employee-salary-index', 'PayrollController@salaryIndex')->name('employee.salary.index')->middleware('can.user:employee.salary.index');
Route::get('employee-salary-create', 'PayrollController@salaryCreate')->name('employee.salary.create')->middleware('can.user:employee.salary.create');
Route::post('employee-salary-store', 'PayrollController@salaryStore')->name('employee.salary.store')->middleware('can.user:employee.salary.create');
Route::get('employee-salary-delete/{id}', 'PayrollController@salaryDestroy')->name('employee.salary.delete')->middleware('can.user:employee.salary.delete');

Route::get('employee-payroll-index', 'PayrollController@index')->name('employee.payroll.index')->middleware('can.user:employee.payroll.index');
Route::get('employee-payroll-create', 'PayrollController@create')->name('employee.payroll.create')->middleware('can.user:employee.payroll.create');
Route::post('employee-payroll-store', 'PayrollController@store')->name('employee.payroll.store')->middleware('can.user:employee.payroll.create');
Route::get('pay-employee-salary/{academicYearId}/{monthId}', 'PayrollController@createPaySalary')->name('pay.employee.salary')->middleware('can.user:pay.employee.salary');
Route::get('employee-payroll-view/{academicYearId}/{monthId}', 'PayrollController@employeePayrollView')->name('employee.payroll.view')->middleware('can.user:employee.payroll.view');
Route::get('employee-payroll-report/{academicYearId}/{monthId}', 'PayrollController@employeePayrollReport')->name('employee.payroll.report')->middleware('can.user:employee.payroll.report');
Route::get('employee-payroll-report/{academicYearId}/{monthId}/{paymentType}', 'PayrollController@employeePayrollReportWithType');
Route::post('employee-payroll-filter', 'PayrollController@employeePayrollFilter')->name('employee.payroll.filter');
Route::post('employee-payroll-update', 'PayrollController@updateEmployeeSalary')->name('employee.payroll.update');
Route::get('allowance-add/{id}/edit', 'PayrollController@allowanceAdd')->name('allowance.add');
Route::post('allowance-update', 'PayrollController@allowanceUpdate')->name('allowance.update');
Route::post('employee-payslip', 'PayrollController@employeePayslip')->name('employee.payslip');

