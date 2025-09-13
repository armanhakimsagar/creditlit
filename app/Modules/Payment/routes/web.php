<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Payment', 'middleware' => ['web', 'redirect_if_logout', 'adminmiddleware']], function () {

    Route::get('expense-chart', 'ExpenseController@expenseChartIndex')->name('expense.chart')->middleware('can.user:expense.chart.index');
    Route::get('create-expense-chart', 'ExpenseController@createExpenseChart')->name('create.expense.chart')->middleware('can.user:expense.chart.create');
    Route::post('store-expense-chart', 'ExpenseController@storeExpenseChart')->name('store.expense.chart')->middleware('can.user:expense.chart.create');
    Route::get('edit-expense-chart/{id}', 'ExpenseController@editExpenseChart')->name('edit.expense.chart')->middleware('can.user:expense.chart.edit');
    Route::patch('update-expense-chart/{id}', 'ExpenseController@updateExpenseChart')->name('update.expense.chart')->middleware('can.user:expense.chart.edit');
    Route::get('delete-expense-chart/{id}', 'ExpenseController@destroyExpenseChart')->name('delete.expense.chart')->middleware('can.user:expense.chart.delete');
    Route::get('/expense-chart-trash', 'ExpenseController@trash')->name('expense.chart.trash')->middleware('can.user:expense.chart.create');
    // Restore Class
    Route::get('/expense-chart-restore/{id}', 'ExpenseController@expense_chart_restore')->name('expense.chart.restore')->middleware('can.user:expense.chart.create');
    //expense
    Route::get('expense-index', 'ExpenseController@expenseIndex')->name('expense.index')->middleware('can.user:expense.index');
    Route::get('create-expense', 'ExpenseController@createExpense')->name('create.expense')->middleware('can.user:expense.create');
    Route::post('get-payment-account', 'ExpenseController@getAccount');
    Route::post('store-expense', 'ExpenseController@storePayment')->name('store.expense')->middleware('can.user:expense.create');
    Route::get('edit-expense/{id}', 'ExpenseController@editExpense')->name('edit.expense')->middleware('can.user:expense.edit');
    Route::patch('update-expense/{id}', 'ExpenseController@updateExpense')->name('update.expense')->middleware('can.user:expense.edit');
    Route::get('delete-expense/{id}', 'ExpenseController@destroyExpense')->name('delete.expense')->middleware('can.user:expense.delete');

    include ('payment.php');

    //Account Category
    include ('accountCategory.php');
    //Account
    include ('account.php');

});
