<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Api\DataFetchController;

use App\Http\Controllers\Admin\Platform\StagesController;
use App\Http\Controllers\Admin\Platform\GradesController;
use App\Http\Controllers\Admin\Platform\SubjectsController;
use App\Http\Controllers\Admin\Platform\PlansController;

use App\Http\Controllers\Admin\Users\Teachers\TeachersController;
use App\Http\Controllers\Admin\Users\Teachers\TeachersDetailsController;
use App\Http\Controllers\Admin\Users\Assistants\AssistantsController;
use App\Http\Controllers\Admin\Users\Assistants\AssistantsDetailsController;
use App\Http\Controllers\Admin\Users\Parents\ParentsController;
use App\Http\Controllers\Admin\Users\Parents\ParentsDetailsController;
use App\Http\Controllers\Admin\Users\Students\StudentsController;
use App\Http\Controllers\Admin\Users\Students\StudentsDetailsController;

use App\Http\Controllers\Admin\Finance\FeesController;
use App\Http\Controllers\Admin\Finance\RefundsController;
use App\Http\Controllers\Admin\Finance\InvoicesController;
use App\Http\Controllers\Admin\Finance\ReceiptsController;

use App\Http\Controllers\Admin\Tools\GroupsController;

use App\Http\Controllers\Admin\Activities\AttendanceController;
use App\Http\Controllers\Admin\Activities\ZoomsController;
use App\Http\Controllers\Admin\Activities\QuizzesController;
use App\Http\Controllers\Admin\Activities\QuestionsController;
use App\Http\Controllers\Admin\Activities\AnswersController;
use App\Http\Controllers\Admin\Activities\AssignmentsController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function(){

        Route::get('/', function () {
            return view('landing.index');
        })->name('landing');
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/developer',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:web']
    ], function(){

    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

    Route::name('admin.')->group(function() {

        # Api Responses
        Route::prefix('fetch')->controller(DataFetchController::class)->name('fetch.')->group(function () {
            Route::get('teachers/{teacher}/grades/{grade}/groups', 'getTeacherGroupsByGrade')->name('teachers.grade.groups');
        });

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
                Route::get('{id}/teachers', 'getGradeTeachers')->name('teachers');
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
                Route::post('price', 'getPlanPrice')->name('price');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
        # End Platform Managment

        # Start Users Managment
            # Teachers
            Route::prefix('teachers')->name('teachers.')->group(function() {
                Route::controller(TeachersController::class)->group(function() {
                    Route::get('/', 'index')->name('index');
                    Route::get('/archived', 'archived')->name('archived');
                    Route::post('groups', 'getTeacherGroups')->name('groups');
                    Route::get('{id}/grades', 'getTeacherGrades')->name('grades');
                    Route::get('{id}/fees', 'getTeacherFees')->name('fees');
                    Route::get('{id}/account-balance', 'getTeacherAccountBalance')->name('accountBalance');
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
                Route::controller(TeachersDetailsController::class)->group(function() {
                    Route::get('/details/{id}', 'index')->name('details');
                    Route::post('/update-profile-pic/{id}', 'updateProfilePic')->name('updateProfilePic');
                });
            });

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
                Route::controller(AssistantsDetailsController::class)->group(function() {
                    Route::get('/details/{id}', 'index')->name('details');
                    Route::post('/update-profile-pic/{id}', 'updateProfilePic')->name('updateProfilePic');
                });
            });

            # Students
            Route::prefix('students')->name('students.')->group(function() {
                Route::controller(StudentsController::class)->group(function() {
                    Route::get('/', 'index')->name('index');
                    Route::get('/archived', 'archived')->name('archived');
                    Route::get('/details/{id}', 'details')->name('details');
                    Route::get('{id}/grade', 'getStudentGrade')->name('grade');
                    Route::get('{id}/teachers', 'getStudentTeachers')->name('teachers');
                    Route::get('{id}/account-balance', 'getStudentAccountBalance')->name('accountBalance');
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
                Route::controller(StudentsDetailsController::class)->group(function() {
                    Route::get('/details/{id}', 'index')->name('details');
                    Route::post('/update-profile-pic/{id}', 'updateProfilePic')->name('updateProfilePic');
                });
            });

            # Parents
            Route::prefix('parents')->name('parents.')->group(function() {
                Route::controller(ParentsController::class)->group(function() {
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
                Route::controller(ParentsDetailsController::class)->group(function() {
                    Route::get('/details/{id}', 'index')->name('details');
                });
            });
        # End Users Managment

        # Start Finance Managment
            # Fees
            Route::prefix('fees')->controller(FeesController::class)->name('fees.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('{id}/amount', 'getFeeAmount')->name('amount');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            # Invoices
            Route::prefix('invoices')->controller(InvoicesController::class)->name('invoices.')->group(function() {
                foreach (['teachers', 'students'] as $type) {
                    Route::prefix($type)->name("$type.")->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::middleware('throttle:10,1')->group(function () {
                            Route::post('insert', 'insert')->name('insert');
                            Route::post('delete', 'delete')->name('delete');
                        });
                    });
                }
            });

            # Receipts
            Route::prefix('receipts')->controller(ReceiptsController::class)->name('receipts.')->group(function() {
                foreach (['teachers', 'students'] as $type) {
                    Route::prefix($type)->name("$type.")->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::middleware('throttle:10,1')->group(function () {
                            Route::post('insert', 'insert')->name('insert');
                            Route::post('update', 'update')->name('update');
                            Route::post('delete', 'delete')->name('delete');
                        });
                    });
                }
            });

            # Refunds
            Route::prefix('refunds')->controller(RefundsController::class)->name('refunds.')->group(function() {
                foreach (['teachers', 'students'] as $type) {
                    Route::prefix($type)->name("$type.")->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::middleware('throttle:10,1')->group(function () {
                            Route::post('insert', 'insert')->name('insert');
                            Route::post('update', 'update')->name('update');
                            Route::post('delete', 'delete')->name('delete');
                        });
                    });
                }
            });
        # End Finance Managment

        # Start Teacher Tools
            # Groups
            Route::prefix('groups')->controller(GroupsController::class)->name('groups.')->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('{id}/students', 'getGroupStudents')->name('students');
                Route::middleware('throttle:10,1')->group(function() {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
        # End Teacher Tools

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
                Route::get('{id}', 'details')->name('details');
                Route::post('{id}/upload', 'uploadFile')->name('files.upload');
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



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/teacher.php';
require __DIR__.'/assistant.php';
require __DIR__.'/student.php';
require __DIR__.'/parent.php';
