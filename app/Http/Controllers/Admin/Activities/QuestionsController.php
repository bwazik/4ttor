<?php

namespace App\Http\Controllers\Admin\Activities;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Activities\QuestionService;
use App\Http\Requests\Admin\Activities\QuestionsRequest;

class QuestionsController extends Controller
{
    use ValidatesExistence;

    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index($quizId)
    {
        $quizExists = Quiz::where('id', $quizId)->exists();
        if (!$quizExists) {
            abort(404);
        }

        $questions = Question::query()->select('id', 'quiz_id', 'question_text')->where('quiz_id', $quizId)->get();

        return view('admin.activities.questions.index', compact('questions', 'quizId'));
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
