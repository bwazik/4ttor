<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Activities\QuestionService;
use App\Http\Requests\Admin\Activities\QuestionsRequest;

class QuestionsController extends Controller
{
    use ValidatesExistence;

    protected $questionService;
    protected $teacherId;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index($quizId)
    {
        $quiz = Quiz::where('id', $quizId)
            ->where('teacher_id', $this->teacherId)->first();

        if (!$quiz) {
            abort(404);
        }

        $questions = Question::query()->select('id', 'quiz_id', 'question_text')->where('quiz_id', $quizId)->get();

        return view('teacher.activities.questions.index', compact('questions', 'quizId'));
    }

    public function insert(QuestionsRequest $request, $quizId)
    {
        $result = $this->questionService->insertQuestion($request->validated(), $quizId);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(QuestionsRequest $request)
    {
        $result = $this->questionService->updateQuestion($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'questions');

        $result = $this->questionService->deleteQuestion($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'questions');

        $result = $this->questionService->deleteSelectedQuestions($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
