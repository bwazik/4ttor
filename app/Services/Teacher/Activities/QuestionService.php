<?php

namespace App\Services\Teacher\Activities;

use App\Models\Question;
use App\Traits\PreventDeletionIfRelated;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PublicValidatesTrait;

class QuestionService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function insertQuestion(array $request, $quizId)
    {
        return $this->executeTransaction(function () use ($request, $quizId)
        {
            if ($validationResult = $this->ensureQuizOwnership($quizId, $this->teacherId))
                return $validationResult;

            if ($validationResult = $this->ensureQuestionLimitNotExceeded($quizId))
                return $validationResult;

            Question::create([
                'quiz_id' => $quizId,
                'question_text' => ['en' => $request['question_text_en'], 'ar' => $request['question_text_ar']],
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/questions.question')]));
        });
    }

    public function updateQuestion($id, array $request): array
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            $question = Question::findOrFail($id);

            if ($validationResult = $this->ensureQuizOwnership($question->quiz_id, $this->teacherId))
                return $validationResult;

            if ($validationResult = $this->ensureQuestionLimitNotExceeded($question->quiz_id))
                return $validationResult;

            $question->update([
                'question_text' => ['en' => $request['question_text_en'], 'ar' => $request['question_text_ar']],
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/questions.question')]));
        });
    }

    public function deleteQuestion($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            Question::findOrFail($id)->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/questions.question')]));
        });
    }

    public function deleteSelectedQuestions($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            Question::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/questions.question')]));
        });
    }
}
