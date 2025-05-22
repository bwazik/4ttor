<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use App\Http\Controllers\Student\Activities\QuizzesController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/student',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:student']
    ], function(){

    Route::name('student.')->group(function() {

        Route::get('/dashboard', function () {
            return view('student.dashboard');
        })->name('dashboard');

        # Account
        Route::prefix('account')->controller(AccountController::class)->name('account.')->group(function () {
            Route::get('personal', 'editPersonalInfo')->name('personal.edit');
            Route::post('update-profile-pic', 'updateProfilePic')->name('updateProfilePic')->middleware('throttle:5,1');
            Route::post('personal', 'updatePersonalInfo')->name('personal.update')->middleware('throttle:5,1');
            Route::get('security', 'securityIndex')->name('security.index');
            Route::post('security/password/update', 'updatePassword')->name('password.update')->middleware('throttle:5,1');
            Route::get('coupons', 'getCoupons')->name('coupons.index');
            Route::post('coupons/redeem', 'redeemCoupon')->name('coupons.redeem')->middleware('throttle:5,1');
        });

        # Quizzes
        Route::prefix('quizzes')->controller(QuizzesController::class)->name('quizzes.')->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{uuid}/notices', 'notices')->name('notices');
            Route::get('/{uuid}/take', 'take')->name('take');
        });
    });
});
