<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Api\DataFetchController;
use App\Http\Controllers\Teacher\Platform\GroupsController;
use App\Http\Controllers\Teacher\Users\AssistantsController;
use App\Http\Controllers\Teacher\Users\StudentsController;
use App\Http\Controllers\Teacher\Users\ParentsController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/teacher',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:teacher']
    ], function(){

    Route::name('teacher.')->group(function() {
        Route::get('/dashboard', function () { return view('teacher.dashboard');})->name('dashboard');

        # Api Responses
        Route::prefix('fetch')->controller(DataFetchController::class)->name('fetch.')->group(function () {
            Route::get('grades/{grade}/groups', 'getTeacherGroupsByGrade')->name('grade.groups');
        });

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
                    Route::middleware('throttle:10,1')->group(function() {
                        Route::post('insert', 'insert')->name('insert');
                        Route::post('update', 'update')->name('update');
                        Route::post('delete', 'delete')->name('delete');
                        Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                    });
                });
            });

            # Students
            Route::prefix('students')->name('students.')->group(function() {
                Route::controller(StudentsController::class)->group(function() {
                    Route::get('/', 'index')->name('index');
                    Route::middleware('throttle:10,1')->group(function() {
                        Route::post('insert', 'insert')->name('insert');
                        Route::post('update', 'update')->name('update');
                        Route::post('delete', 'delete')->name('delete');
                        Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                    });
                });
            });

            # Parents
            Route::prefix('parents')->name('parents.')->group(function() {
                Route::controller(ParentsController::class)->group(function() {
                    Route::get('/', 'index')->name('index');
                    Route::middleware('throttle:10,1')->group(function() {
                        Route::post('insert', 'insert')->name('insert');
                        Route::post('update', 'update')->name('update');
                        Route::post('delete', 'delete')->name('delete');
                        Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                    });
                });
            });
        # End Users Managment
    });
});
