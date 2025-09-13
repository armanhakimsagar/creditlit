<?php

use Illuminate\Support\Facades\Route;

Route::get('certificate', 'CertificateController@welcome');

Route::group(['module' => 'Admin', 'middleware' => ['web', 'redirect_if_logout', 'adminmiddleware']], function () {
    Route::get('transfer-certificate-index', 'CertificateController@transferCertificateIndex')->name('transfer.certificate.index')->middleware('can.user:transfer.certificate.index');
    Route::get('get-student-certificate', 'CertificateController@getStudentCertificate')->name('get.students');
    Route::post('generate-certificate', 'CertificateController@generateCertificte')->name('generate.certificate');
    Route::post('get-sections', 'CertificateController@getSections')->name('get.sections');
    // Testimonial
    Route::get('testimonial-index', 'CertificateController@testimonialIndex')->name('testimonial.index')->middleware('can.user:testimonial.index');
    Route::post('generate-testimonial-certificate', 'CertificateController@generatetestimonialCertificte')->name('generate.testimonial.certificate');

    // Studentship
    Route::get('studentship-index', 'CertificateController@studentshipIndex')->name('studentship.index')->middleware('can.user:studentship.index');
    Route::post('generate-studentship-certificate', 'CertificateController@generateStudentshipCertificte')->name('generate.studentship.certificate');

    // for exam seat seat
    Route::get('exam-seat-index', 'CertificateController@examSeatIndex')->name('exam.seat.index')->middleware('can.user:exam.seat.index');
    Route::post('exam-seat-generate', 'CertificateController@generateExamSeat')->name('exam.seat.generate');

    // For Admit card
    Route::get('admit-card-index', 'CertificateController@admitCardIndex')->name('admit.card.index')->middleware('can.user:admit.card.index');
    Route::post('admit-card-generate', 'CertificateController@generateAdmitCard')->name('admit.card.generate');
});
