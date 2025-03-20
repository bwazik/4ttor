<?php

namespace App\Http\Controllers\Admin\Activities;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Activities\AnswerService;
use App\Http\Requests\Admin\Activities\AnswersRequest;

class AnswersController extends Controller
{
    use ValidatesExistence;

    protected $answerService;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
    }

    public function index(Request $request, $questionId)
    {
        $questionExists = Question::where('id', $questionId)->exists();
        if (!$questionExists) {
            return response()->json(['error' => trans('notfpund')], 404);
        }

        $answersQuery = Answer::query()->where('question_id', $questionId)->select('id', 'question_id', 'answer_text', 'is_correct', 'score');

        if ($request->ajax()) {
            return $this->answerService->getAnswersForDatatable($answersQuery);
        }
    }

    public function insert(AnswersRequest $request, $questionId)
    {
        $result = $this->answerService->insertAnswer($request->validated(), $questionId);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(AnswersRequest $request)
    {
        $result = $this->answerService->updateAnswer($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'answers');

        $result = $this->answerService->deleteAnswer($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
