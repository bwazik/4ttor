<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use App\Http\Controllers\Api\DataFetchController;

use App\Http\Controllers\Teacher\Tools\GroupsController;
use App\Http\Controllers\Teacher\Tools\ResourcesController;

use App\Http\Controllers\Teacher\Users\AssistantsController;
use App\Http\Controllers\Teacher\Users\StudentsController;
use App\Http\Controllers\Teacher\Users\ParentsController;

use App\Http\Controllers\Teacher\Activities\AttendanceController;
use App\Http\Controllers\Teacher\Activities\ZoomsController;
use App\Http\Controllers\Teacher\Activities\QuizzesController;
use App\Http\Controllers\Teacher\Activities\QuestionsController;
use App\Http\Controllers\Teacher\Activities\AnswersController;
use App\Http\Controllers\Teacher\Activities\AssignmentsController;

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

        # Start Tools
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

            # Resources
            Route::prefix('resources')->controller(ResourcesController::class)->name('resources.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('{uuid}', 'details')->name('details');
                Route::post('{uuid}/upload', 'uploadFile')->name('upload');
                Route::get('{uuid}/download', 'downloadFile')->name('download');
                Route::post('files/delete', 'deleteFile')->name('files.delete');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                });
            });
        # End Tools

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

        # Start Activities
            # Attendance
            Route::prefix('attendance')->controller(AttendanceController::class)->name('attendance.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('students', 'getStudentsByFilter')->name('students');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                });
            });

            # Zooms
            Route::prefix('zooms')->controller(ZoomsController::class)->name('zooms.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            # Quizzes
            Route::prefix('quizzes')->controller(QuizzesController::class)->name('quizzes.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            # Questions
            Route::prefix('quizzes/{quizId}/questions')->controller(QuestionsController::class)->name('questions.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                });
            });
            Route::prefix('questions')->controller(QuestionsController::class)->name('questions.')->middleware('throttle:10,1')->group(function() {
                Route::post('update', 'update')->name('update');
                Route::post('delete', 'delete')->name('delete');
                Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
            });

            # Answers
            Route::prefix('questions/{questionId}/answers')->controller(AnswersController::class)->name('answers.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                });
            });
            Route::prefix('answers')->controller(AnswersController::class)->name('answers.')->middleware('throttle:10,1')->group(function() {
                Route::post('update', 'update')->name('update');
                Route::post('delete', 'delete')->name('delete');
            });

            # Assignments
            Route::prefix('assignments')->controller(AssignmentsController::class)->name('assignments.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('{uuid}', 'details')->name('details');
                Route::post('{uuid}/upload', 'uploadFile')->name('files.upload');
                Route::get('files/{fileId}/download', 'downloadFile')->name('files.download');
                Route::post('files/delete', 'deleteFile')->name('files.delete');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
        # End Activities
    });
});
