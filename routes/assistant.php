<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/assistant',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:assistant']
    ], function(){

    Route::name('assistant.')->group(function() {

        Route::get('/dashboard', function () {
            return view('assistant.dashboard');
        })->name('dashboard');

    });
});
