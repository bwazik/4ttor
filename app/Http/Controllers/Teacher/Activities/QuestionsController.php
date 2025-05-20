<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use App\Services\Teacher\Activities\QuestionService;
use App\Http\Requests\Admin\Activities\QuestionsRequest;

class QuestionsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $questionService;
    protected $teacherId;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index($quizId)
    {
        $quiz = Quiz::uuid($quizId)
            ->where('teacher_id', $this->teacherId)
            ->firstOrFail();

        $questions = Question::query()->select('id', 'quiz_id', 'question_text')->where('quiz_id', $quiz->id)->get();

        return view('teacher.activities.questions.index', compact('questions', 'quizId'));
    }

    public function insert(QuestionsRequest $request, $quizId)
    {
        $realQuizId = Quiz::uuid($quizId)
            ->where('teacher_id', $this->teacherId)
            ->value('id');

        $result = $this->questionService->insertQuestion($request->validated(), $realQuizId);

        return $this->conrtollerJsonResponse($result);
    }

    public function update(QuestionsRequest $request)
    {
        $result = $this->questionService->updateQuestion($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result);
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
