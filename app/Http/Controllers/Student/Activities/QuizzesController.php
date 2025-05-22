<?php

namespace App\Http\Controllers\Student\Activities;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\StudentResult;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use App\Services\Student\Activities\QuizService;

class QuizzesController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $studentId;
    protected $studentGradeId;
    protected $studentGroupIds;
    protected $teacherIds;
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
        $student = auth()->guard('student')->user();
        $this->studentId = $student->id;
        $this->studentGradeId = $student->grade_id;
        $this->studentGroupIds = $student->groups()->pluck('groups.id')->toArray();
        $this->teacherIds = $student->teachers()->pluck('teachers.id')->toArray();
    }

    public function index(Request $request)
    {
        $quizzesQuery = Quiz::query()->with(['teacher:id,name'])
            ->select('id', 'uuid', 'name', 'teacher_id', 'duration', 'start_time', 'end_time')
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
        });

        if ($request->ajax()) {
            return $this->quizService->getQuizzesForDatatable($quizzesQuery);
        }

        return view('student.activities.quizzes.index');
    }

    public function notices($uuid)
    {
        $quiz = Quiz::with(['grade:id,name', 'teacher:id,name,subject_id,profile_pic', 'teacher.subject:id,name'])
            ->uuid($uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })
            ->withCount('questions')
            ->firstOrFail();


        if (!now()->between($quiz->start_time, $quiz->end_time)) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNotAvailable'));
        }

        return view('student.activities.quizzes.notices', compact('quiz'));
    }

    public function take($uuid)
    {
        $quiz = Quiz::where('uuid', $uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })
            ->with(['questions', 'questions.answers'])
            ->firstOrFail();

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->first();

        if (!now()->between($quiz->start_time, $quiz->end_time)) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNotAvailable'));
        }

        if (!$result) {
            $result = StudentResult::create([
                'student_id' => $this->studentId,
                'quiz_id' => $quiz->id,
                'total_score' => 0,
                'attempt_number' => 1,
                'started_at' => now(),
            ]);
        }

        return view('student.activities.quizzes.take', compact('quiz', 'result'));
    }
}
