<?php

namespace App\Services\Teacher\Activities;

use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Group;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class QuizService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function getQuizzesForDatatable($quizzesQuery)
    {
        return datatables()->eloquent($quizzesQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->uuid))
            ->editColumn('name', fn($row) => $row->name)
            ->editColumn('grade_id', fn($row) => formatRelation($row->grade_id, $row->grade, 'name'))
            ->addColumn('duration', fn($row) => formatDuration($row->duration))
            ->editColumn('start_time', fn($row) => isoFormat($row->start_time))
            ->editColumn('end_time', fn($row) => isoFormat($row->end_time))
            ->addColumn('actions', fn($row) => $this->generateActionButtons($row))
            ->filterColumn('grade_id', fn($query, $keyword) => filterByRelation($query, 'grade', 'name', $keyword))
            ->rawColumns(['selectbox', 'actions'])
            ->make(true);
    }

    private function generateActionButtons($row)
    {
        $groupIds = $row->groups->pluck('uuid')->toArray();
        $groups = implode(',', $groupIds);

        return
            '<div class="d-inline-block">' .
                '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' .
                    '<i class="ri-more-2-line"></i>' .
                '</a>' .
                '<ul class="dropdown-menu dropdown-menu-end m-0">' .
                    '<li>
                        <a target="_blank" href="' . route('teacher.questions.index', $row->uuid) . '" class="dropdown-item">'.trans('admin/questions.questions').'</a>
                    </li>' .
                    '<div class="dropdown-divider"></div>' .
                    '<li>' .
                        '<a href="javascript:;" class="dropdown-item text-danger" ' .
                            'id="delete-button" ' .
                            'data-id="' . $row->uuid . '" ' .
                            'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                            'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                            'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                            trans('main.delete') .
                        '</a>' .
                    '</li>' .
                '</ul>' .
            '</div>' .
            '<button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light" ' .
                'tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal" ' .
                'id="edit-button" ' .
                'data-id="' . $row->uuid . '" ' .
                'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                'data-grade_id="' . $row->grade_id . '" ' .
                'data-groups="' . $groups . '" ' .
                'data-duration="' . $row->duration . '" ' .
                'data-start_time="' . humanFormat($row->start_time) . '" ' .
                'data-end_time="' . humanFormat($row->end_time) . '">' .
                '<i class="ri-edit-box-line ri-20px"></i>' .
            '</button>';
    }

    public function insertQuiz(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            $groupIds = Group::whereIn('uuid', $request['groups'])->pluck('id')->toArray();

            if ($validationResult = $this->validateTeacherGradeAndGroups($this->teacherId, $groupIds, $request['grade_id'], true))
                return $validationResult;

            $quiz = Quiz::create([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'teacher_id' => $this->teacherId,
                'grade_id' => $request['grade_id'],
                'duration' => $request['duration'],
                'start_time' => $request['start_time'],
                'end_time' => Carbon::parse($request['start_time'])->addMinutes((int) $request['duration']),
            ]);

            $quiz->groups()->attach($groupIds);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/quizzes.quiz')]));
        });
    }

    public function updateQuiz($id, array $request): array
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            $groupIds = Group::whereIn('uuid', $request['groups'])->pluck('id')->toArray();

            if ($validationResult = $this->validateTeacherGradeAndGroups($this->teacherId, $groupIds, $request['grade_id'], true))
                return $validationResult;

            $quiz = Quiz::findOrFail($id);
            $quiz->update([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'grade_id' => $request['grade_id'],
                'duration' => $request['duration'],
                'start_time' => $request['start_time'],
                'end_time' => Carbon::parse($request['start_time'])->addMinutes((int) $request['duration']),
            ]);

            $quiz->groups()->sync($groupIds ?? []);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/quizzes.quiz')]));
        });
    }

    public function deleteQuiz($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            Quiz::findOrFail($id)->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/quizzes.quiz')]));
        });
    }

    public function deleteSelectedQuizzes($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            Quiz::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/quizzes.quiz')]));
        });
    }
}
