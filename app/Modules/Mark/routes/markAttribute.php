<?php
use Illuminate\Support\Facades\Route;

//    Mark Attribute Route
        Route::get('mark-attribute-index', 'MarkController@markAttributeIndex')->name('mark.attribute.index')->middleware('can.user:mark.attribute.index');
        Route::get('mark-attribute-create', 'MarkController@markAttributeCreate')->name('mark.attribute.create')->middleware('can.user:mark.attribute.create');
        Route::post('mark-attribute-store', 'MarkController@markAttributeStore')->name('mark.attribute.store')->middleware('can.user:mark.attribute.create');
        Route::get('mark-attribute-edit/{id}', 'MarkController@markAttributeEdit')->name('mark.attribute.edit')->middleware('can.user:mark.attribute.edit');
        Route::patch('mark-attribute-update/{id}', 'MarkController@markAttributeUpdate')->name('mark.attribute.update')->middleware('can.user:mark.attribute.edit');
        Route::get('mark-attribute-delete/{id}', 'MarkController@markAttributeDestroy')->name('mark.attribute.delete')->middleware('can.user:mark.attribute.delete');
        
//    Mark Config Route
        Route::post('get-subjects', 'MarkController@getSubject');
        Route::post('get-exams', 'MarkController@getExam');
        Route::post('get-mark-header', 'MarkController@getMarkHeader');
        Route::get('mark-config-index', 'MarkController@markConfigIndex')->name('mark.config.index')->middleware('can.user:mark.config.index');
        Route::get('mark-config-create', 'MarkController@markConfigCreate')->name('mark.config.create');
        Route::post('mark-config-store', 'MarkController@markConfigStore')->name('mark.config.store');
        
        Route::get('student-mark-input-index', 'MarkController@studentMarkInputIndex')->name('student.mark.input.index')->middleware('can.user:student.mark.input'); 
        Route::post('student-mark-input-store', 'MarkController@studentMarkInputStore')->name('student.mark.input.store'); 
        Route::get('/view-config/{id}', 'MarkController@configHistory')->name('view.config');
        Route::delete('/view-config-delete/{id}', 'MarkController@viewConfigDestroy')->name('view.config.delete');


        
        ?>