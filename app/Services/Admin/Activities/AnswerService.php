<?php

namespace App\Services\Admin\Activities;

use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class AnswerService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['studentAnswers'];

    protected $transModelKey = 'admin/answers.answers';

    public function getAnswersForDatatable($answersQuery)
    {
        return datatables()->eloquent($answersQuery)
            ->addIndexColumn()
            ->editColumn('answer_text', function ($row) {
                return $row->answer_text;
            })
            ->editColumn('is_correct', function ($row) {
                return $row->is_correct ? '<span class="badge rounded-pill bg-label-success" text-capitalized="">'.trans('main.correct').'</span>' : '<span class="badge rounded-pill bg-label-danger" text-capitalized="">'.trans('main.wrong').'</span>';
            })
            ->addColumn('actions', function ($row) {
                return
                    '<div class="align-items-center">' .
                    '<span class="text-nowrap">
                        <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                            tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-answer-modal"
                            id="edit-answer-button" data-id="' . $row->id . '" data-question_id="' . $row->question_id . '"
                            data-answer_text_ar="' . $row->getTranslation('answer_text', 'ar') . '"
                            data-answer_text_en="' . $row->getTranslation('answer_text', 'en') . '"
                            data-is_correct="' . ($row->is_correct == 0 ? '0' : '1') . '" data-score="' . $row->score . '">
                            <i class="ri-edit-box-line ri-20px"></i>
                        </button>
                    </span>' .
                    '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                            id="delete-answer-button" data-id=' . $row->id . ' data-question_id="' . $row->question_id . '"
                            data-answer_text_ar="' . $row->getTranslation('answer_text', 'ar') . '"
                            data-answer_text_en="' . $row->getTranslation('answer_text', 'en') . '"
                            data-bs-target="#delete-answer-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                        </button>' .
                    '</div>';
            })
            ->rawColumns(['is_correct', 'actions'])
            ->make(true);
    }

    public function insertAnswer(array $request, $questionId)
    {
        DB::beginTransaction();

        try {

            if (Answer::where('question_id', $questionId)->count() >= 5) {
                return ['status' => 'error', 'message' => trans('admin/answers.questionHasMaxAnswers')];
            }

            Answer::create([
                'question_id' => $questionId,
                'answer_text' => ['en' => $request['answer_text_en'], 'ar' => $request['answer_text_ar']],
                'is_correct' => $request['is_correct'],
                'score' => $request['score'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/answers.answer')]),
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

    public function updateAnswer($id, array $request)
    {
        DB::beginTransaction();

        try {
            $answer = Answer::findOrFail($id);

            if (Answer::where('question_id', $answer->question_id)->count() >= 5) {
                return ['status' => 'error', 'message' => trans('admin/answers.questionHasMaxAnswers')];
            }

            $answer->update([
                'answer_text' => ['en' => $request['answer_text_en'], 'ar' => $request['answer_text_ar']],
                'is_correct' => $request['is_correct'],
                'score' => $request['score'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/answers.answer')]),
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

    public function deleteAnswer($id): array
    {
        DB::beginTransaction();

        try {
            $answer = Answer::select('id', 'answer_text')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($answer)) {
                return $dependencyCheck;
            }

            $answer->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/answers.answer')]),
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

    public function checkDependenciesForSingleDeletion($answer)
    {
        return $this->checkForSingleDependencies($answer, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($answers)
    {
        return $this->checkForMultipleDependencies($answers, $this->relationships, $this->transModelKey);
    }
}
