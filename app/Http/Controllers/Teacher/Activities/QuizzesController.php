<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Quiz;
use App\Models\Grade;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Activities\QuizService;
use App\Http\Requests\Admin\Activities\QuizzesRequest;

class QuizzesController extends Controller
{
    use ValidatesExistence;

    protected $quizService;
    protected $teacherId;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $quizzesQuery = Quiz::query()
            ->select('id', 'grade_id', 'name', 'duration', 'start_time', 'end_time')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->quizService->getQuizzesForDatatable($quizzesQuery);
        }

        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        $groups = Group::query()
            ->select('id', 'name', 'grade_id')
            ->where('teacher_id', $this->teacherId)
            ->with('grade:id,name')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(fn($group) => [$group->id => $group->name . ' - ' . $group->grade->name]);

        return view('teacher.activities.quizzes.index', compact('grades', 'groups'));
    }

    public function insert(QuizzesRequest $request)
    {
        $result = $this->quizService->insertQuiz($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(QuizzesRequest $request)
    {
        $result = $this->quizService->updateQuiz($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'quizzes');

        $result = $this->quizService->deleteQuiz($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'quizzes');

        $result = $this->quizService->deleteSelectedQuizzes($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
