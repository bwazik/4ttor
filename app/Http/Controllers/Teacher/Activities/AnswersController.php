<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\PublicValidatesTrait;
use App\Traits\ServiceResponseTrait;
use App\Services\Teacher\Activities\AnswerService;
use App\Http\Requests\Admin\Activities\AnswersRequest;

class AnswersController extends Controller
{
    use ValidatesExistence, PublicValidatesTrait, ServiceResponseTrait;

    protected $answerService;
    protected $teacherId;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request, $questionId)
    {
        $question = Question::where('id', $questionId)->select('id', 'quiz_id')->first();

        if (!$question) {
            return response()->json(['error' => trans('notfound')], 404);
        }

        if ($validationResult = $this->ensureQuizOwnership($question->quiz_id, $this->teacherId))
            return $validationResult;

        $answersQuery = Answer::query()->where('question_id', $questionId)->select('id', 'question_id', 'answer_text', 'is_correct', 'score');

        if ($request->ajax()) {
            return $this->answerService->getAnswersForDatatable($answersQuery);
        }
    }

    public function insert(AnswersRequest $request, $questionId)
    {
        $result = $this->answerService->insertAnswer($request->validated(), $questionId);

        return $this->conrtollerJsonResponse($result);
    }

    public function update(AnswersRequest $request)
    {
        $result = $this->answerService->updateAnswer($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'answers');

        $result = $this->answerService->deleteAnswer($request->id);

        return $this->conrtollerJsonResponse($result);
    }
}
