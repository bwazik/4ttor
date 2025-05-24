<?php

namespace App\Http\Controllers\Student\Activities;

use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\StudentResult;
use App\Models\StudentQuizOrder;
use App\Models\StudentViolation;
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
        $quiz = Quiz::with(['grade:id,name', 'teacher:id,name,phone,profile_pic'])
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

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->first();

        // if ($result && $result->status == 2) {
        //     return redirect()->route('student.quizzes.review', $quiz->uuid)->with('info', trans('toasts.quizAlreadyCompleted'));
        // }

        return view('student.activities.quizzes.notices', compact('quiz', 'result'));
    }

    public function take($uuid, $questionOrder = null)
    {
        // 1. Fetch the Quiz with Relations
        $quiz = Quiz::uuid($uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })
            ->with(['questions', 'teacher:id,name'])
            ->withCount('questions')
            ->firstOrFail();

        // 3. Check Student’s Quiz Result
        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->first();

        // 2. Check Quiz Availability
        if ($quiz->quiz_mode == 1 && $quiz->duration > 0 && now()->greaterThanOrEqualTo(Carbon::parse($quiz->end_time))) {
            $result = StudentResult::where('student_id', $this->studentId)
                ->where('quiz_id', $quiz->id)
                ->first();
            if ($result) {
                $result->update(['status' => 2, 'completed_at' => now()]);
            }
            return request()->expectsJson()
                ? response()->json(['error' => trans('toasts.quizTimeExpired'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizTimeExpired'));
        }
        if ($quiz->quiz_mode == 2 && $result && $quiz->duration > 0 && Carbon::parse($result->started_at)->addMinutes($quiz->duration) < now()) {
            $result->update(['status' => 2, 'completed_at' => now()]);
            return request()->expectsJson()
                ? response()->json(['error' => trans('toasts.quizTimeExpired'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizTimeExpired'));
        }

        if (!now()->greaterThanOrEqualTo($quiz->start_time)) {
            return response()->json(['error' => trans('toasts.quizNotAvailable'), 'redirect' => route('student.quizzes.index')], 403);
        }


        // 4. Check if Quiz is Already Completed
        if ($result && $result->status == 2) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizAlreadyCompleted'));
        }

        // 5. Initialize New Result if None Exists
        if (!$result) {
            if ($quiz->questions_count === 0) {
                return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNoQuestions'));
            }
            $result = StudentResult::create([
                'student_id' => $this->studentId,
                'quiz_id' => $quiz->id,
                'total_score' => 0,
                'percentage' => 0,
                'started_at' => now(),
                'status' => 1,
                'last_order' => 1,
            ]);

            $this->quizService->initializeQuizOrder($result, $quiz);

            // Verify questions were initialized
            if (
                !StudentQuizOrder::where('student_id', $this->studentId)
                    ->where('quiz_id', $quiz->id)
                    ->exists()
            ) {
                $result->delete(); // Clean up invalid result
                return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNoQuestions'));
            }
        }

        // 6. Check for Time Expiration
        if ($quiz->quiz_mode == 1 && $quiz->duration > 0 && Carbon::parse($quiz->end_time) < now()) {
            $result->update(['status' => 2, 'completed_at' => now()]);
            return response()->json(['error' => trans('toasts.quizTimeExpired'), 'redirect' => route('student.quizzes.index')], 403);
        }
        if ($quiz->quiz_mode == 2 && $quiz->duration > 0 && Carbon::parse($result->started_at)->addMinutes($quiz->duration) < now()) {
            $result->update(['status' => 2, 'completed_at' => now()]);
            return response()->json(['error' => trans('toasts.quizTimeExpired'), 'redirect' => route('student.quizzes.index')], 403);
        }

        // 7. Calculate Remaining Time for View
        $timeRemaining = null;
        if ($quiz->quiz_mode == 1 && $quiz->duration > 0) {
            $timeRemaining = now()->diffInSeconds(Carbon::parse($quiz->end_time), false);
            if ($timeRemaining < 0) {
                $timeRemaining = 0;
            }
        } elseif ($quiz->quiz_mode == 2 && $quiz->duration > 0) {
            $endTime = Carbon::parse($result->started_at)->addMinutes($quiz->duration);
            $timeRemaining = now()->diffInSeconds($endTime, false);
            if ($timeRemaining < 0) {
                $timeRemaining = 0;
            }
        }

        // 8. Determine Current Question Order
        $currentOrder = $questionOrder ?? $result->last_order ?? 1;
        if ($questionOrder && $questionOrder > $result->last_order + 1) {
            return response()->json(['error' => trans('toasts.questionNotAccessible')], 403);
        }

        // 9. Update last_order in StudentResult
        $result->update(['last_order' => max($currentOrder, $result->last_order)]);

        // 10. Fetch Current Question Order
        $quizOrder = StudentQuizOrder::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->where('display_order', $currentOrder)
            ->with(['question', 'question.answers'])
            ->first();

        // 11. Handle No Question Found
        if (!$quizOrder) {
            $answeredCount = StudentAnswer::where('student_id', $this->studentId)
                ->where('quiz_id', $quiz->id)
                ->count();
            if ($answeredCount >= $quiz->questions->count()) {
                $result->update(['status' => 2, 'completed_at' => now()]);
                return response()->json(['status' => 'success', 'message' => trans('toasts.quizCompleted')], 200);
            }
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNoQuestionsRemaining'));
        }

        // 12. Get Question and Answers
        $question = $quizOrder->question;
        $answers = $quiz->randomize_answers && $quizOrder->answer_order
            ? $question->answers()
                ->whereIn('id', json_decode($quizOrder->answer_order))
                ->get()
                ->sortBy(function ($answer) use ($quizOrder) {
                    return array_search($answer->id, json_decode($quizOrder->answer_order));
                })
            : $question->answers;

        // 13. Get Previous Answer
        $previousAnswer = StudentAnswer::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->where('question_id', $question->id)
            ->first();

        // 14. Get Answered Question IDs
        $answeredQuestionIds = StudentAnswer::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->pluck('question_id')
            ->toArray();

        // 15. Get All Quiz Orders
        $quizOrders = StudentQuizOrder::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->orderBy('display_order')
            ->get();

        // 16. Calculate Total Score
        $quiz->total_score = $quiz->questions->flatMap(function ($question) {
            return $question->answers->pluck('score');
        })->sum();

        // 17. Prepare Response
        $responseData = [
            'success' => true,
            'quiz' => [
                'name' => $quiz->name,
                'uuid' => $quiz->uuid,
                'questions_count' => $quiz->questions_count,
                'last_order' => $result->last_order,
            ],
            'question' => [
                'id' => $question->id,
                'text' => $question->question_text,
            ],
            'answers' => $answers->map(function ($answer) use ($previousAnswer) {
                return [
                    'id' => $answer->id,
                    'text' => $answer->answer_text,
                    'checked' => $previousAnswer && $previousAnswer->answer_id == $answer->id,
                ];
            })->values()->toArray(),
            'current_order' => $currentOrder,
            'time_remaining' => $timeRemaining,
            'quiz_mode' => $quiz->quiz_mode,
            'answered_question_ids' => $answeredQuestionIds,
            'quiz_orders' => $quizOrders->map(function ($order) {
                return [
                    'display_order' => $order->display_order,
                    'question_id' => $order->question_id,
                ];
            })->toArray(),
        ];

        if (request()->expectsJson()) {
            return response()->json($responseData);
        }

        // 18. Render the View
        return view('student.activities.quizzes.take', compact(
            'quiz',
            'result',
            'question',
            'answers',
            'currentOrder',
            'previousAnswer',
            'answeredQuestionIds',
            'quizOrders',
            'timeRemaining',
        ));
    }

    public function submitAnswer(Request $request, $uuid)
    {
        $quiz = Quiz::uuid($uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })
            ->firstOrFail();

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        if ($quiz->quiz_mode == 1 && $quiz->duration > 0 && now()->greaterThanOrEqualTo(Carbon::parse($quiz->end_time))) {
            $result->update(['status' => 2, 'completed_at' => now()]);
            return request()->expectsJson()
                ? response()->json(['error' => trans('toasts.quizTimeExpired'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizTimeExpired'));
        }
        if ($quiz->quiz_mode == 2 && $quiz->duration > 0 && Carbon::parse($result->started_at)->addMinutes($quiz->duration) < now()) {
            $result->update(['status' => 2, 'completed_at' => now()]);
            return request()->expectsJson()
                ? response()->json(['error' => trans('toasts.quizTimeExpired'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizTimeExpired'));
        }
        if (!now()->greaterThanOrEqualTo($quiz->start_time) || $result->status == 2) {
            return response()->json(['error' => trans('toasts.quizNotAvailable'), 'redirect' => route('student.quizzes.index')], 403);
        }

        $nextOrder = $request->current_order + 1;
        if ($nextOrder > $result->last_order + 1) {
            return response()->json(['error' => trans('toasts.questionNotAccessible')], 403);
        }

        $request->validate([
            'question_id' => [
                'required',
                'integer',
                'exists:questions,id',
                function ($attribute, $value, $fail) use ($quiz) {
                    // Ensure question belongs to the quiz
                    if (!Question::where('id', $value)->where('quiz_id', $quiz->id)->exists()) {
                        $fail(trans('toasts.invalidQuestionForQuiz'));
                    }
                },
            ],
            'answer_id' => [
                'required',
                'integer',
                'exists:answers,id',
                function ($attribute, $value, $fail) use ($request) {
                    // Ensure answer belongs to the question
                    if (!Answer::where('id', $value)->where('question_id', $request->question_id)->exists()) {
                        $fail(trans('toasts.invalidAnswerForQuestion'));
                    }
                },
            ],
            'current_order' => 'required|integer|min:1',
        ]);

        // Verify question is in the student's quiz order
        $questionOrder = StudentQuizOrder::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->where('question_id', $request->question_id)
            ->where('display_order', $request->current_order)
            ->exists();

        if (!$questionOrder) {
            return response()->json(['error' => trans('toasts.invalidQuestionOrder')], 403);
        }

        StudentAnswer::updateOrCreate(
            [
                'student_id' => $this->studentId,
                'quiz_id' => $quiz->id,
                'question_id' => $request->question_id,
            ],
            [
                'answer_id' => $request->answer_id,
                'answered_at' => now(),
            ]
        );

        $this->quizService->updateStudentResult($result);

        $nextOrder = $request->current_order + 1;
        $isLast = !StudentQuizOrder::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->where('display_order', $nextOrder)
            ->exists();

        if (!$isLast) {
            return response()->json([
                'success' => true,
                'next_order' => $nextOrder,
                'is_last' => false,
            ], 200);
        }

        $result->update(['status' => 2, 'completed_at' => now()]);

        return response()->json(['success' => trans('toasts.quizCompleted'), 'is_last' => true], 200);
    }

    public function violation(Request $request, $uuid)
    {
        $quiz = Quiz::uuid($uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })
            ->firstOrFail();

        $request->validate([
            'violation_type' => 'required|string|max:255',
        ]);

        StudentViolation::create([
            'student_id' => $this->studentId,
            'quiz_id' => $quiz->id,
            'violation_type' => $request->violation_type,
            'detected_at' => now(),
        ]);

        return response()->json(['success' => true], 200);
    }

    public function review($uuid)
    {
        $quiz = Quiz::where('uuid', $uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })
            ->firstOrFail();

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        if ($result->status != 2 || (!$quiz->show_result && !$quiz->allow_review)) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.reviewNotAvailable'));
        }

        $answers = StudentAnswer::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->with(['question', 'answer'])
            ->join('student_quiz_order', function ($join) {
                $join->on('student_answers.student_id', '=', 'student_quiz_order.student_id')
                    ->on('student_answers.quiz_id', '=', 'student_quiz_order.quiz_id')
                    ->on('student_answers.question_id', '=', 'student_quiz_order.question_id');
            })
            ->orderBy('student_quiz_order.display_order')
            ->get();

        return view('student.activities.quizzes.review', compact('quiz', 'result', 'answers'));
    }
}

