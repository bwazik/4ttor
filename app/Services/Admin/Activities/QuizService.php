<?php

namespace App\Services\Admin\Activities;

use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class QuizService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['questions', 'studentAnswers', 'studentResults', 'studentViolations'];

    protected $transModelKey = 'admin/quizzes.quizzes';

    public function getQuizzesForDatatable($quizzesQuery)
    {
        return datatables()->eloquent($quizzesQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('teacher_id', function ($row) {
                return "<a target='_blank' href='" . route('admin.teachers.details', $row->teacher_id) . "'>" . ($row->teacher_id ? $row->teacher->name : '-') . "</a>";
            })
            ->editColumn('grade_id', function ($row) {
                return $row->grade_id ? $row->grade->name : '-';
            })
            ->editColumn('duration', function ($row) {
                $minutes = $row->duration;
                $hours = floor($minutes / 60);
                $remainingMinutes = $minutes % 60;

                if ($hours > 0) {
                    return $hours . ' ' . trans('admin/zooms.hours') . '' .
                    ($remainingMinutes > 0 ? ' ' . trans('admin/zooms.and') . ' ' .
                    $remainingMinutes . ' ' . trans('admin/zooms.minute') . '' : '');
                }
                return $remainingMinutes . ' ' . trans('admin/zooms.minutes') . '';
            })
            ->editColumn('start_time', function ($row) {
                return isoFormat($row->start_time);
            })
            ->editColumn('end_time', function ($row) {
                return isoFormat($row->end_time);
            })
            ->addColumn('actions', function ($row) {
                $groupIds = $row->groups->pluck('id')->toArray();
                $groups = implode(',', $groupIds);

                return
                    '<div class="align-items-center">' .
                    '<span class="text-nowrap">
                        <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                            tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal"
                            id="edit-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                            data-teacher_id="' . $row->teacher_id . '" data-grade_id="' . $row->grade_id . '" data-groups="' . $groups . '"
                            data-duration="' . $row->duration . '" data-start_time="' . humanFormat($row -> start_time) . '"
                            data-end_time="' . humanFormat($row -> end_time) . '">
                            <i class="ri-edit-box-line ri-20px"></i>
                        </button>
                    </span>' .
                    '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                            id="delete-button" data-id=' . $row->id . ' data-meeting_id=' . $row->meeting_id . '
                            data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                            data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                        </button>' .
                    '</div>';
            })
            ->rawColumns(['selectbox', 'teacher_id', 'actions'])
            ->make(true);
    }

    public function insertQuiz(array $request)
    {
        DB::beginTransaction();

        try {

            if ($validationError = $this->verifyTeacherAuthorization($request)) {
                return $validationError;
            }

            $quiz = Quiz::create([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
                'duration' => $request['duration'],
                'start_time' => $request['start_time'],
                'end_time' => Carbon::parse($request['start_time'])->addMinutes((int) $request['duration']),
            ]);

            $quiz->groups()->attach($request['groups']);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/quizzes.quiz')]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function updateQuiz($id, array $request): array
    {
        DB::beginTransaction();

        try {
            if ($validationError = $this->verifyTeacherAuthorization($request)) {
                return $validationError;
            }

            $quiz = Quiz::findOrFail($id);
            $quiz->update([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
                'duration' => $request['duration'],
                'start_time' => $request['start_time'],
                'end_time' => Carbon::parse($request['start_time'])->addMinutes((int) $request['duration']),
            ]);

            $quiz->groups()->sync($request['groups'] ?? []);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/quizzes.quiz')]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function deleteQuiz($id): array
    {
        DB::beginTransaction();

        try {
            $quiz = Quiz::select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($quiz)) {
                return $dependencyCheck;
            }

            $quiz->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/quizzes.quiz')]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function deleteSelectedQuizzes($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $quizzes = Quiz::whereIn('id', $ids)
                ->select('id', 'name')
                ->orderBy('id')
                ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($quizzes)) {
                return $dependencyCheck;
            }

            Quiz::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/quizzes.quizzes'))]),
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function checkDependenciesForSingleDeletion($quiz)
    {
        return $this->checkForSingleDependencies($quiz, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($quizzes)
    {
        return $this->checkForMultipleDependencies($quizzes, $this->relationships, $this->transModelKey);
    }

    private function verifyTeacherAuthorization(array $request): ?array
    {
        $isAuthorized = Teacher::where('id', $request['teacher_id'])
            ->whereHas('grades', function ($query) use ($request) {
                $query->where('grades.id', $request['grade_id']);
            })
            ->whereHas('groups', function ($query) use ($request) {
                $query->whereIn('groups.id', $request['groups'])
                    ->where('groups.grade_id', $request['grade_id']);
            })
            ->exists();

        if (!$isAuthorized) {
            return [
                'status' => 'error',
                'message' => trans('main.validateTeacherGradesGroups'),
            ];
        }

        $validGroupCount = Group::whereIn('id', $request['groups'])
            ->where('teacher_id', $request['teacher_id'])
            ->where('grade_id', $request['grade_id'])
            ->count();

        if ($validGroupCount !== count($request['groups'])) {
            return [
                'status' => 'error',
                'message' => trans('main.validateTeacherGroups'),
            ];
        }

        return null;
    }

}
