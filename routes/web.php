<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StagesController;
use App\Http\Controllers\Admin\GradesController;
use App\Http\Controllers\Admin\SubjectsController;
use App\Http\Controllers\Admin\PlansController;
use App\Http\Controllers\Admin\Teachers\TeachersController;
use App\Http\Controllers\Admin\Teachers\GroupsController;
use App\Http\Controllers\Admin\Students\StudentsController;
use App\Http\Controllers\Admin\Parents\ParentsController;
use App\Http\Controllers\Admin\Assistants\AssistantsController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'guest']
    ], function(){

        Route::get('/', function () {
            return view('landing.index');
        });
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/developer',
        'name' => 'admin.',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth', 'verified']
    ], function(){

    Route::name('admin.')->group(function() {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        # Start Platform Managment
            # Stages
            Route::prefix('stages')->controller(StagesController::class)->name('stages.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
            # Grades
            Route::prefix('grades')->controller(GradesController::class)->name('grades.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
            # Subjects
            Route::prefix('subjects')->controller(SubjectsController::class)->name('subjects.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
            # Plans
            Route::prefix('plans')->controller(PlansController::class)->name('plans.')->group(function() {
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
            # Manage Teachers
            Route::prefix('teachers')->controller(TeachersController::class)->name('teachers.')->group(function() {
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

            # Groups
            Route::prefix('groups')->controller(GroupsController::class)->name('groups.')->group(function() {
                Route::get('/', 'index')->name('index');
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

            # Manage Assistants
            Route::prefix('assistants')->controller(AssistantsController::class)->name('assistants.')->group(function() {
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

            # Manage Students
            Route::prefix('students')->controller(StudentsController::class)->name('students.')->group(function() {
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

            # Manage Parents
            Route::prefix('parents')->controller(ParentsController::class)->name('parents.')->group(function() {
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
        # End Users Managment
    });
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
