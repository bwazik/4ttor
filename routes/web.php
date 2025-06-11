<?php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;

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
use App\Http\Controllers\Admin\Users\Students\StudentsController;
use App\Http\Controllers\Admin\Users\Students\StudentsDetailsController;
use App\Http\Controllers\Admin\Users\Parents\ParentsController;
use App\Http\Controllers\Admin\Users\Parents\ParentsDetailsController;

use App\Http\Controllers\Admin\Tools\GroupsController;
use App\Http\Controllers\Admin\Tools\LessonsController;
use App\Http\Controllers\Admin\Tools\ResourcesController;

use App\Http\Controllers\Admin\Activities\AttendanceController;
use App\Http\Controllers\Admin\Activities\ZoomsController;
use App\Http\Controllers\Admin\Activities\QuizzesController;
use App\Http\Controllers\Admin\Activities\QuestionsController;
use App\Http\Controllers\Admin\Activities\AnswersController;
use App\Http\Controllers\Admin\Activities\AssignmentsController;

use App\Http\Controllers\Admin\Finance\FeesController;
use App\Http\Controllers\Admin\Finance\StudentFeesController;
use App\Http\Controllers\Admin\Finance\InvoicesController;
use App\Http\Controllers\Admin\Finance\TransactionsController;
use App\Http\Controllers\Admin\Finance\CouponsController;
use App\Http\Controllers\Admin\Finance\TeacherSubscriptionsController;
use App\Http\Controllers\Admin\Finance\TeachersInvoicesController;

use App\Http\Controllers\Admin\Misc\CategoriesController;
use App\Http\Controllers\Admin\Misc\FaqsController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Route::controller(LandingController::class)->group(function () {
            Route::get('/', 'index')->name('landing');
            Route::middleware('throttle:1,5')->group(function () {
                Route::post('contact-us', 'contact')->name('landing.contact');
            });
        });
    }
);

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . '/developer',
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:web']
    ],
    function () {


        Route::name('admin.')->group(function () {
            Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

            # Api Responses
            Route::prefix('fetch')->controller(DataFetchController::class)->name('fetch.')->group(function () {
                Route::get('teachers/{teacher}/grades/{grade}/groups', 'getTeacherGroupsByGrade')->name('teachers.grade.groups');
                Route::get('teachers/{teacher}/teacher-subscriptions', 'getTeacherSubscriptionsByTeacher')->name('teachers.teacher-subscriptions');
                Route::get('teachers/{teacher}', 'getTeacherData')->name('teachers.data');
                Route::get('students/{student}', 'getStudentData')->name('students.data');
                Route::get('students/{student}/fees', 'getStudentFeesByStudent')->name('students.fees');
                Route::get('students/{student}/student-fees', 'getStudentRegisteredFeesByStudent')->name('students.student-fees');
                Route::get('fees/{fee}', 'getFeeData')->name('fees.data');
                Route::get('student-fees/{studentFee}', 'getStudentFeeData')->name('student-fees.data');
                Route::get('teacher-subscriptions/{teacherSubscription}', 'getTeacherSubscriptionData')->name('teacher-subscriptions.data');
                Route::get('plans/{plan}/{period?}', 'getPlanData')->name('plans.data');
                Route::get('groups/{group}/lessons', 'getGroupLessons')->name('groups.lessons');
                Route::get('lessons/{lesson}', 'getLessonData')->name('lessons.data');
            });

        # Start Platform Managment
            # Stages
            Route::prefix('stages')->controller(StagesController::class)->name('stages.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
            # Grades
            Route::prefix('grades')->controller(GradesController::class)->name('grades.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
            # Subjects
            Route::prefix('subjects')->controller(SubjectsController::class)->name('subjects.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
            # Plans
            Route::prefix('plans')->controller(PlansController::class)->name('plans.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('price', 'getPlanPrice')->name('price');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
        # End Platform Managment

        # Start Users Managment
            # Teachers
            Route::prefix('teachers')->name('teachers.')->group(function () {
                Route::controller(TeachersController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/archived', 'archived')->name('archived');
                    Route::get('{teacherId}/grades', 'grades')->name('grades');
                    Route::get('{teacherId}/grades/{gradeId}/groups', 'getTeacherGroupsByGrade')->name('grades.groups');
                    Route::post('groups', 'getTeacherGroups')->name('groups');
                    Route::get('{id}/get-grades', 'getTeacherGrades')->name('getGrades');
                    Route::middleware('throttle:10,1')->group(function () {
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
                Route::controller(TeachersDetailsController::class)->group(function () {
                    Route::get('/details/{id}', 'index')->name('details');
                    Route::post('/update-profile-pic/{id}', 'updateProfilePic')->name('updateProfilePic');
                });
            });

            # Assistants
            Route::prefix('assistants')->name('assistants.')->group(function () {
                Route::controller(AssistantsController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/archived', 'archived')->name('archived');
                    Route::middleware('throttle:10,1')->group(function () {
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
                Route::controller(AssistantsDetailsController::class)->group(function () {
                    Route::get('/details/{id}', 'index')->name('details');
                    Route::post('/update-profile-pic/{id}', 'updateProfilePic')->name('updateProfilePic');
                });
            });

            # Students
            Route::prefix('students')->name('students.')->group(function () {
                Route::controller(StudentsController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/archived', 'archived')->name('archived');
                    Route::get('/details/{id}', 'details')->name('details');
                    Route::get('{id}/grade', 'getStudentGrade')->name('grade');
                    Route::get('{id}/teachers', 'getStudentTeachers')->name('teachers');
                    Route::middleware('throttle:10,1')->group(function () {
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
                Route::controller(StudentsDetailsController::class)->group(function () {
                    Route::get('/details/{id}', 'index')->name('details');
                    Route::post('/update-profile-pic/{id}', 'updateProfilePic')->name('updateProfilePic');
                });
            });

            # Parents
            Route::prefix('parents')->name('parents.')->group(function () {
                Route::controller(ParentsController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/archived', 'archived')->name('archived');
                    Route::middleware('throttle:10,1')->group(function () {
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
                Route::controller(ParentsDetailsController::class)->group(function () {
                    Route::get('/details/{id}', 'index')->name('details');
                });
            });
        # End Users Managment

        # Start Teacher Tools
            # Groups
            Route::prefix('groups')->controller(GroupsController::class)->name('groups.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{groupId}/lessons', 'lessons')->name('lessons');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            # Lessons
            Route::prefix('lessons')->controller(LessonsController::class)->name('lessons.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{lessonId}/attendances', 'attendances')->name('attendances');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            # Resources
            Route::prefix('resources')->controller(ResourcesController::class)->name('resources.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{id}', 'details')->name('details');
                Route::post('{id}/upload', 'uploadFile')->name('upload');
                Route::get('{id}/download', 'downloadFile')->name('download');
                Route::post('files/delete', 'deleteFile')->name('files.delete');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                });
            });
        # End Teacher Tools

        # Start Activities
            # Attendance
            Route::prefix('attendance')->controller(AttendanceController::class)->name('attendance.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('students', 'getStudentsByFilter')->name('students');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                });
            });

            # Zooms
            Route::prefix('zooms')->controller(ZoomsController::class)->name('zooms.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            # Quizzes
            Route::prefix('quizzes')->controller(QuizzesController::class)->name('quizzes.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                    Route::post('{id}/students/{studentId}/reset', 'resetStudentQuiz')->name('resetStudentQuiz');
                });
                Route::get('{id}/reports', 'reports')->name('reports');
                Route::get('{id}/students/{studentId}/review', 'review')->name('review');
                Route::get('{id}/students-taken-quiz', 'studentsTakenQuiz')->name('studentsTakenQuiz');
                Route::get('{id}/students-not-taken-quiz', 'studentsNotTakenQuiz')->name('studentsNotTakenQuiz');
            });

            # Questions
            Route::prefix('quizzes/{quizId}/questions')->controller(QuestionsController::class)->name('questions.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                });
            });
            Route::prefix('questions')->controller(QuestionsController::class)->name('questions.')->middleware('throttle:10,1')->group(function () {
                Route::post('update', 'update')->name('update');
                Route::post('delete', 'delete')->name('delete');
                Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
            });

            # Answers
            Route::prefix('questions/{questionId}/answers')->controller(AnswersController::class)->name('answers.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                });
            });
            Route::prefix('answers')->controller(AnswersController::class)->name('answers.')->middleware('throttle:10,1')->group(function () {
                Route::post('update', 'update')->name('update');
                Route::post('delete', 'delete')->name('delete');
            });

            # Assignments
            Route::prefix('assignments')->controller(AssignmentsController::class)->name('assignments.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{id}', 'details')->name('details');
                Route::get('files/{fileId}/download', 'downloadFile')->name('files.download');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                    Route::post('{id}/upload', 'uploadFile')->name('files.upload');
                    Route::post('files/delete', 'deleteFile')->name('files.delete');
                    Route::post('{id}/students/{studentId}/feedback', 'feedback')->name('feedback');
                    Route::post('{id}/students/{studentId}/reset', 'resetStudentAssignment')->name('resetStudentAssignment');
                });
                Route::get('{id}/reports', 'reports')->name('reports');
                Route::get('{id}/students/{studentId}/review', 'review')->name('review');
                Route::get('{id}/students-took-assignment', 'studentsTookAssignment')->name('studentsTookAssignment');
                Route::get('{id}/students-havenot-taken-assignment', 'studentsHavenotTakenAssignment')->name('studentsHavenotTakenAssignment');
                Route::get('submissions/{fileId}/download', 'downloadSubmission')->name('submissions.download');
                Route::get('submissions/{fileId}/view', 'viewSubmission')->name('submissions.view');
            });
        # End Activities


        # Start Finance Managment
            Route::prefix('fees')->controller(FeesController::class)->name('fees.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            Route::prefix('student-fees')->controller(StudentFeesController::class)->name('student-fees.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            Route::prefix('teacher-subscriptions')->controller(TeacherSubscriptionsController::class)->name('teacher-subscriptions.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });

            Route::prefix('invoices/students')->controller(InvoicesController::class)->name('invoices.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/archived', 'archived')->name('archived');
                Route::get('{id}/print', 'print')->name('print');
                Route::get('create', 'create')->name('create');
                Route::get('{id}', 'preview')->name('preview');
                Route::get('{id}/edit', 'edit')->name('edit');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('{id}/update', 'update')->name('update');
                    Route::post('{id}/payment', 'payment')->name('payment');
                    Route::post('{id}/refund', 'refund')->name('refund');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('cancel', 'cancel')->name('cancel');
                    Route::post('archive', 'archive')->name('archive');
                    Route::post('restore', 'restore')->name('restore');
                });
            });
            Route::prefix('invoices/teachers')->controller(TeachersInvoicesController::class)->name('invoices.teachers.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/archived', 'archived')->name('archived');
                Route::get('{id}/print', 'print')->name('print');
                Route::get('create', 'create')->name('create');
                Route::get('{id}', 'preview')->name('preview');
                Route::get('{id}/edit', 'edit')->name('edit');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('{id}/update', 'update')->name('update');
                    Route::post('{id}/payment', 'payment')->name('payment');
                    Route::post('{id}/refund', 'refund')->name('refund');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('cancel', 'cancel')->name('cancel');
                    Route::post('archive', 'archive')->name('archive');
                    Route::post('restore', 'restore')->name('restore');
                });
            });

            Route::prefix('transactions')->controller(TransactionsController::class)->name('transactions.')->group(function () {
                Route::get('/students', 'students')->name('students');
                Route::get('/teachers', 'teachers')->name('teachers');
            });

            Route::prefix('coupons')->controller(CouponsController::class)->name('coupons.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
        # End Finance Managment

        # Start Misc
            Route::prefix('categories')->controller(CategoriesController::class)->name('categories.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
            Route::prefix('faqs')->controller(FaqsController::class)->name('faqs.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::middleware('throttle:10,1')->group(function () {
                    Route::post('insert', 'insert')->name('insert');
                    Route::post('update', 'update')->name('update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('delete-selected', 'deleteSelected')->name('deleteSelected');
                });
            });
        # End Misc
        });
    }
);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/teacher.php';
require __DIR__ . '/assistant.php';
require __DIR__ . '/student.php';
require __DIR__ . '/parent.php';
