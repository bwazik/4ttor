<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Teacher\Platform\GroupsController;
use App\Http\Controllers\Teacher\Users\AssistantsController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/teacher',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:teacher']
    ], function(){

    Route::name('teacher.')->group(function() {
        Route::get('/dashboard', function () { return view('teacher.dashboard');})->name('dashboard');

        # Start Platform Managment
            # Groups
            Route::prefix('groups')->controller(GroupsController::class)->name('groups.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
        # End Platform Managment

        # Start Users Managment
            # Assistants
            Route::prefix('assistants')->name('assistants.')->group(function() {
                Route::controller(AssistantsController::class)->group(function() {
                    Route::get('/', 'index')->name('index');
                    Route::get('/archived', 'archived')->name('archived');
                    Route::middleware('throttle:10,1')->group(function() {
                        Route::post('insert', 'insert')->name('insert');
                        Route::post('update', 'update')->name('update');
                        Route::post('delete', 'delete')->name('delete');
                        Route::post('archive', 'archive')->name('archive');
                        Route::post('restore', 'restore')->name('restore');
                        Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                        Route::post('archive-selected', 'archiveSelected')->name('archiveSelected');
                        Route::post('restore-selected', 'restoreSelected')->name('restoreSelected');
                    });
                });
            });
        # End Users Managment
    });
});
