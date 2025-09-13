<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Admin', 'middleware' => ['web', 'redirect_if_logout', 'adminmiddleware']], function () {


	Route::get('dynamic-sms', 'SmsController@dynamicSmsIndex')->name('dynamic.sms.index')->middleware('can.user:dynamic.sms');;
	Route::get('guardian-sms', 'SmsController@guardianSmsIndex')->name('guardian.sms.index')->middleware('can.user:guardian.sms');;
	Route::get('teacher-sms', 'SmsController@teacherSmsIndex')->name('teacher.sms')->middleware('can.user:teacher.sms');;
	Route::post('send-dynamic-sms','SmsController@sendSms')->name('send.dynamic.sms');
	Route::post('send-guardian-sms','SmsController@guardianSendSms')->name('guardian.send.sms');
	Route::post('teacher-send-sms','SmsController@teacherSendSms')->name('teacher.send.sms');

	// Due SMS
	Route::get('due-sms', 'SmsController@dueSmsIndex')->name('due.sms.index')->middleware('can.user:due.sms');;
	Route::post('send-due-sms','SmsController@dueSendSms')->name('due.send.sms');

    // Owner SMS
    Route::get('owner-sms-index', 'SmsController@ownerSmsIndex')->name('owner.sms.index');
    Route::post('owner-send-sms', 'SmsController@ownerSendSms')->name('owner.send.sms');

});
