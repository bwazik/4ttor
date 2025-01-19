<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/parent',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:parent']
    ], function(){

    Route::name('parent.')->group(function() {

        Route::get('/dashboard', function () {
            return view('teacher.dashboard');
        })->name('dashboard');

    });
});
