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
use App\Traits\ServiceResponseTrait;

class QuizzesController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $quizService;
    protected $teacherId;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $quizzesQuery = Quiz::query()->with(['grade:id,name'])
            ->select('id', 'uuid', 'grade_id', 'name', 'duration', 'start_time', 'end_time')
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
            ->select('id', 'uuid', 'name', 'grade_id')
            ->where('teacher_id', $this->teacherId)
            ->with('grade:id,name')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(fn($group) => [$group->uuid => $group->name . ' - ' . $group->grade->name]);

        return view('teacher.activities.quizzes.index', compact('grades', 'groups'));
    }

    public function insert(QuizzesRequest $request)
    {
        $result = $this->quizService->insertQuiz($request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function update(QuizzesRequest $request)
    {
        $id = Quiz::uuid($request->id)->value('id');

        $result = $this->quizService->updateQuiz($id, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function delete(Request $request)
    {
        $id = Quiz::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'quizzes');

        $result = $this->quizService->deleteQuiz($request->id);

        return $this->conrtollerJsonResponse($result);
    }

    public function deleteSelected(Request $request)
    {
        $ids = Quiz::whereIn('uuid', $request->ids ?? [])->pluck('id')->toArray();
        !empty($ids) ? $request->merge(['ids' => $ids]) : null;

        $this->validateExistence($request, 'quizzes');

        $result = $this->quizService->deleteSelectedQuizzes($request->ids);

        return $this->conrtollerJsonResponse($result);
    }
}
