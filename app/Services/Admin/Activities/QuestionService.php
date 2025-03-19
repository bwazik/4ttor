<?php

namespace App\Services\Admin\Activities;

use Carbon\Carbon;
use App\Models\Question;
use App\Models\Group;
use App\Models\Teacher;
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

            $question = Question::create([
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
