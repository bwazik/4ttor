<?php

namespace App\Services\Admin\Activities;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class QuestionService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['answers', 'studentAnswers'];

    protected $transModelKey = 'admin/questions.questions';

    public function insertQuestion(array $request, $quizId)
    {
        DB::beginTransaction();

        try {
            if (Question::where('quiz_id', $quizId)->count() >= 50) {
                return ['status' => 'error', 'message' => trans('admin/questions.quizHasMaxQuestions')];
            }

            Question::create([
                'quiz_id' => $quizId,
                'question_text' => ['en' => $request['question_text_en'], 'ar' => $request['question_text_ar']],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/questions.question')]),
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

    public function updateQuestion($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $question = Question::findOrFail($id);

            if (Question::where('quiz_id', $question->quiz_id)->count() >= 50) {
                return ['status' => 'error', 'message' => trans('admin/questions.quizHasMaxQuestions')];
            }

            $question->update([
                'question_text' => ['en' => $request['question_text_en'], 'ar' => $request['question_text_ar']],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/questions.question')]),
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

    public function deleteQuestion($id): array
    {
        DB::beginTransaction();

        try {
            $question = Question::select('id', 'question_text')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($question)) {
                return $dependencyCheck;
            }

            $question->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/questions.question')]),
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

    public function deleteSelectedQuestions($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $questions = Question::whereIn('id', $ids)
                ->select('id', 'question_text')
                ->orderBy('id')
                ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($questions)) {
                return $dependencyCheck;
            }

            Question::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/questions.questions'))]),
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

    public function checkDependenciesForSingleDeletion($question)
    {
        return $this->checkForSingleDependencies($question, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($questions)
    {
        return $this->checkForMultipleDependencies($questions, $this->relationships, $this->transModelKey);
    }
}
