<?php

namespace App\Services\Admin\Activities;

use App\Models\Question;
use App\Traits\PreventDeletionIfRelated;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PublicValidatesTrait;

class QuestionService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = ['answers', 'studentAnswers'];

    protected $transModelKey = 'admin/questions.questions';

    public function insertQuestion(array $request, $quizId)
    {
        return $this->executeTransaction(function () use ($request, $quizId)
        {
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
            $question = Question::select('id', 'question_text')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($question))
                return $dependencyCheck;

            $question->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/questions.question')]));
        });
    }

    public function deleteSelectedQuestions($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            $questions = Question::whereIn('id', $ids)
                ->select('id', 'question_text')
                ->orderBy('id')
                ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($questions))
                return $dependencyCheck;

            Question::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/questions.question')]));
        });
    }

    public function checkDependenciesForSingleDeletion($question)
    {
        return $this->checkForSingleDependencies($question, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($questions)
    {
        return $this->checkForMultipleDependencies($questions, $this->relationships, $this->transModelKey);
    }
}
