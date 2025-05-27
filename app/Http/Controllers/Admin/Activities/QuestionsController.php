<?php

namespace App\Http\Controllers\Admin\Activities;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use App\Services\Admin\Activities\QuestionService;
use App\Http\Requests\Admin\Activities\QuestionsRequest;

class QuestionsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index($quizId)
    {
        $quiz = Quiz::where('id', $quizId)->first();

        if (!$quiz) {
            abort(404);
        }

        $questions = Question::query()->select('id', 'quiz_id', 'question_text')->where('quiz_id', $quizId)->get();

        return view('admin.activities.questions.index', compact('questions', 'quizId'));
    }

    public function insert(QuestionsRequest $request, $quizId)
    {
        $result = $this->questionService->insertQuestion($request->validated(), $quizId);

        return $this->conrtollerJsonResponse($result);
    }

    public function update(QuestionsRequest $request)
    {
        $quizId = Question::findOrFail($request->id)->value('quiz_id');

        $result = $this->questionService->updateQuestion($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result, "quiz_total_score_{$quizId}");
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'questions');

        $result = $this->questionService->deleteQuestion($request->id);

        return $this->conrtollerJsonResponse($result);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'questions');

        $result = $this->questionService->deleteSelectedQuestions($request->ids);

        return $this->conrtollerJsonResponse($result);
    }
}
