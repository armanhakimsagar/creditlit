
<?php
use Illuminate\Support\Facades\Route;

        Route::get('mark-grade-index', 'MarkController@markGradeIndex')->name('mark.grade.index')->middleware('can.user:mark.grade.index');
        Route::get('mark-grade-create', 'MarkController@markGradeCreate')->name('mark.grade.create')->middleware('can.user:mark.grade.create');
        Route::post('mark-grade-store', 'MarkController@markGradeStore')->name('mark.grade.store')->middleware('can.user:mark.grade.create');
        Route::get('mark-grade-edit/{id}', 'MarkController@markGradeEdit')->name('mark.grade.edit')->middleware('can.user:mark.grade.create');
        Route::patch('mark-grade-update/{id}', 'MarkController@markGradeUpdate')->name('mark.grade.update')->middleware('can.user:mark.grade.create');
        Route::get('mark-grade-delete/{id}', 'MarkController@markGradeDestroy')->name('mark.grade.delete')->middleware('can.user:mark.grade.delete');
        
        Route::get('student-marksheet-index', 'MarkController@markSheetIndex')->name('student.marksheet.index')->middleware('can.user:student.marksheet');
        Route::post('student-marksheet-generate', 'MarkController@markSheetGenerate')->name('student.marksheet.generate');
        ?>