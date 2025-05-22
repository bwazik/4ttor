<?php

namespace App\Http\Controllers\Admin\Activities;

use App\Models\Quiz;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Activities\QuizService;
use App\Http\Requests\Admin\Activities\QuizzesRequest;

class QuizzesController extends Controller
{
    use ValidatesExistence;

    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index(Request $request)
    {
        $quizzesQuery = Quiz::query()
            ->select('id', 'teacher_id', 'grade_id', 'name', 'duration', 'quiz_mode', 'start_time', 'end_time',
                'randomize_questions', 'randomize_answers', 'show_result', 'allow_review');

        if ($request->ajax()) {
            return $this->quizService->getQuizzesForDatatable($quizzesQuery);
        }

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $groups = Group::query()->select('id', 'name', 'teacher_id', 'grade_id')
            ->with(['teacher:id,name', 'grade:id,name'])
            ->orderBy('teacher_id')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(function ($group) {
                $gradeName = $group->grade->name ?? 'N/A';
                $teacherName = $group->teacher->name ?? 'N/A';
                return [$group->id => $group->name . ' - ' . $gradeName . ' - ' . $teacherName];
            });

        return view('admin.activities.quizzes.index', compact('teachers', 'grades', 'groups'));
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
