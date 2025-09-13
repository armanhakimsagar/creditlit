<?php

use Illuminate\Support\Facades\Route;

Route::get('company', 'CompanyController@welcome');
Route::group(['module' => 'Admin', 'middleware' => ['web', 'redirect_if_logout', 'adminmiddleware']], function () {
    Route::get('institution-create', 'CompanyController@create')->name('institution.create');
    Route::get('institution-index', 'CompanyController@index')->name('institution.index')->middleware('can.user:institution.index');
    Route::post('institution-store', 'CompanyController@store')->name('institution.store');
    Route::get('institution-edit/{id}', 'CompanyController@edit')->name('institution.edit')->middleware('can.user:institution.edit');
    Route::get('institution-show/{id}', 'CompanyController@show')->name('institution.show');
    Route::patch('institution-update/{id}', 'CompanyController@update')->name('institution.update');

    //Settings Only
    Route::get('institution-settings-edit/{id}', 'CompanyController@institutionSetting')->name('institution.settings')->middleware('can.user:institution.setting.edit');
    Route::get('sms-settings', 'CompanyController@smsSetting')->name('sms.setting')->middleware('can.user:sms.setting');
    Route::get('sid-create', 'CompanyController@sidCreate')->name('sid.create');
    Route::get('sid-settings-edit/{id}', 'CompanyController@sidSetting')->name('sid.setting')->middleware('can.user:sid.setting');
    Route::get('/fine-settings', 'CompanyController@fineSetting')->name('fine.settings')->middleware('can.user:fine.setting');
    Route::post('/fine-settings-store', 'CompanyController@fineSettingStore')->name('fine.setting.store');
    Route::patch('customize-sid-update/{id}', 'CompanyController@sidUpdate')->name('customize.sid.update');
    Route::post('customize-sid-store', 'CompanyController@customizeSidStore')->name('customize.sid.store');
    Route::post('admin-sms-store', 'CompanyController@adminSmsStore')->name('admin.sms.store');
    Route::post('save-student-collection-data', 'CompanyController@saveStudentCollectionData')->name('save.student.collection.data');
});
