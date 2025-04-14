<?php

namespace App\Services\Admin\Activities;

use App\Models\Answer;
use App\Traits\PreventDeletionIfRelated;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PublicValidatesTrait;


class AnswerService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = ['studentAnswers'];

    protected $transModelKey = 'admin/answers.answers';

    public function getAnswersForDatatable($answersQuery)
    {
        return datatables()->eloquent($answersQuery)
            ->addIndexColumn()
            ->editColumn('answer_text', fn($row) => $row->answer_text)
            ->editColumn('is_correct', fn($row) => formatCorrectionStatus($row->is_correct))
            ->addColumn('actions', fn($row) => $this->generateActionButtons($row))
            ->rawColumns(['is_correct', 'actions'])
            ->make(true);
    }

    private function generateActionButtons($row): string
    {
        return
            '<div class="align-items-center">' .
                '<span class="text-nowrap">' .
                    '<button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light" ' .
                        'tabindex="0" type="button" ' .
                        'data-bs-toggle="offcanvas" data-bs-target="#edit-answer-modal" ' .
                        'id="edit-answer-button" ' .
                        'data-id="' . $row->id . '" ' .
                        'data-question_id="' . $row->question_id . '" ' .
                        'data-answer_text_ar="' . $row->getTranslation('answer_text', 'ar') . '" ' .
                        'data-answer_text_en="' . $row->getTranslation('answer_text', 'en') . '" ' .
                        'data-is_correct="' . ($row->is_correct ? '1' : '0') . '" ' .
                        'data-score="' . $row->score . '" ' . '">' .
                        '<i class="ri-edit-box-line ri-20px"></i>' .
                    '</button>' .
                '</span>' .
                '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1" ' .
                    'id="delete-answer-button" ' .
                    'data-id="' . $row->id . '" ' .
                    'data-question_id="' . $row->question_id . '" ' .
                    'data-answer_text_ar="' . $row->getTranslation('answer_text', 'ar') . '" ' .
                    'data-answer_text_en="' . $row->getTranslation('answer_text', 'en') . '" ' .
                    'data-bs-target="#delete-answer-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                    '<i class="ri-delete-bin-7-line ri-20px text-danger"></i>' .
                '</button>' .
            '</div>';
    }

    public function insertAnswer(array $request, $questionId)
    {
        return $this->executeTransaction(function () use ($request, $questionId)
        {
            if ($validationResult = $this->ensureAnswerLimitNotExceeded($questionId))
                return $validationResult;

            Answer::create([
                'question_id' => $questionId,
                'answer_text' => ['en' => $request['answer_text_en'], 'ar' => $request['answer_text_ar']],
                'is_correct' => $request['is_correct'],
                'score' => $request['score'],
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/answers.answer')]));
        });
    }

    public function updateAnswer($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            $answer = Answer::findOrFail($id);

            if ($validationResult = $this->ensureAnswerLimitNotExceeded($answer->question_id))
                return $validationResult;

            $answer->update([
                'answer_text' => ['en' => $request['answer_text_en'], 'ar' => $request['answer_text_ar']],
                'is_correct' => $request['is_correct'],
                'score' => $request['score'],
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/answers.answer')]));
        });
    }

    public function deleteAnswer($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            $answer = Answer::select('id', 'answer_text')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($answer))
                return $dependencyCheck;

            $answer->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/answers.answer')]));
        });
    }

    public function checkDependenciesForSingleDeletion($answer)
    {
        return $this->checkForSingleDependencies($answer, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($answers)
    {
        return $this->checkForMultipleDependencies($answers, $this->relationships, $this->transModelKey);
    }
}
