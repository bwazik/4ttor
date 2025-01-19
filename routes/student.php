<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/student',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:student']
    ], function(){

    Route::name('student.')->group(function() {

        Route::get('/dashboard', function () {
            return view('student.dashboard');
        })->name('dashboard');

    });
});
