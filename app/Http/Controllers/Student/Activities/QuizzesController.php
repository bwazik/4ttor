<?php

namespace App\Http\Controllers\Student\Activities;

use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\StudentResult;
use App\Services\GeminiService;
use App\Models\StudentQuizOrder;
use App\Models\StudentViolation;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Traits\DatabaseTransactionTrait;
use App\Services\Student\Activities\QuizService;

class QuizzesController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait, DatabaseTransactionTrait;

    protected $quizService;
    protected $geminiService;
    protected $student;
    protected $studentId;
    protected $studentGradeId;
    protected $studentGroupIds;
    protected $teacherIds;

    public function __construct(QuizService $quizService, GeminiService $geminiService)
    {
        $this->quizService = $quizService;
        $this->geminiService = $geminiService;
        $this->student = auth()->guard('student')->user();
        $this->studentId = $this->student->id;
        $this->studentGradeId = $this->student->grade_id;
        $this->studentGroupIds = $this->student->groups()->pluck('groups.id')->toArray();
        $this->teacherIds = $this->student->teachers()->pluck('teachers.id')->toArray();
    }

    public function index(Request $request)
    {
        $quizzesQuery = Quiz::query()->with(['teacher:id,name'])
            ->select(
                'id',
                'uuid',
                'name',
                'teacher_id',
                'duration',
                'quiz_mode',
                'start_time',
                'end_time',
                'show_result',
                'allow_review'
            )
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

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->first();

        $isAvailable = false;
        if ($quiz->quiz_mode == 1) {
            $isAvailable = now()->between($quiz->start_time, $quiz->end_time);
        } elseif ($quiz->quiz_mode == 2) {
            if ($result) {
                $endTime = Carbon::parse($result->started_at)->addMinutes($quiz->duration);
                $isAvailable = now()->lessThan($endTime);
            } else {
                $isAvailable = now()->between($quiz->start_time, $quiz->end_time);
            }
        }

        if ($result && ($result->status == 2 || $result->status == 3) && ($quiz->show_result || $quiz->allow_review)) {
            return redirect()->route('student.quizzes.review', $quiz->uuid);
        }

        if (!$isAvailable) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNotAvailable'));
        }

        if ($quiz->questions_count === 0) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNoQuestions'));
        }

        $quiz->total_score = $quiz->questions->flatMap(function ($question) {
            return $question->answers->pluck('score');
        })->sum();

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

        // 4. Check if Quiz is Already Completed
        if ($result && ($result->status == 2 || $result->status == 3)) {
            return request()->expectsJson()
                ? response()->json(['success' => trans('toasts.quizAlreadyCompleted'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('success', trans('toasts.quizAlreadyCompleted'));
        }

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
            return request()->expectsJson()
                ? response()->json(['error' => trans('toasts.quizNotAvailable'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('error', trans('toasts.quizNotAvailable'));
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

            Cache::put("heartbeat:{$this->studentId}:{$quiz->id}", now(), 120);

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
            return request()->expectsJson()
                ? response()->json(['error' => trans('toasts.questionNotAccessible'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('error', trans('toasts.questionNotAccessible'));
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

        if ($result && ($result->status == 2 || $result->status == 3)) {
            return request()->expectsJson()
                ? response()->json(['success' => trans('toasts.quizAlreadyCompleted'), 'redirect' => route('student.quizzes.index')], 403)
                : redirect()->route('student.quizzes.index')->with('success', trans('toasts.quizAlreadyCompleted'));
        }

        // Check heartbeat cache
        $key = "heartbeat:{$this->studentId}:{$quiz->id}";
        if ($result->status == 1 && !Cache::has($key) && $request->current_order > 1) {
            StudentViolation::create([
                'student_id' => $this->studentId,
                'quiz_id' => $quiz->id,
                'violation_type' => 'tampering',
                'detected_at' => now(),
            ]);
            $violationCount = StudentViolation::where('student_id', $this->studentId)
                ->where('quiz_id', $quiz->id)
                ->count();
            if ($violationCount >= 5) {
                $result->update(['status' => 3, 'completed_at' => now()]);
                return response()->json([
                    'error' => trans('toasts.tooManyViolations'),
                    'redirect' => route('student.quizzes.index')
                ], 403);
            }
        }

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

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        $request->validate([
            'violation_type' => 'required|string|in:tab_switch,focus_loss,copy,paste,context_menu,shortcut,screenshot,dev_tools,tampering',
        ]);

        StudentViolation::create([
            'student_id' => $this->studentId,
            'quiz_id' => $quiz->id,
            'violation_type' => $request->violation_type,
            'detected_at' => now(),
        ]);

        $violationCount = StudentViolation::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($violationCount >= 5) {
            $result->update(['status' => 3, 'completed_at' => now()]);
            return response()->json([
                'error' => trans('toasts.tooManyViolations'),
                'redirect' => route('student.quizzes.index')
            ], 403);
        }

        return response()->json(['success' => true], 200);
    }

    public function cheatDetector(Request $request, $uuid)
    {
        $quiz = Quiz::uuid($uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })->firstOrFail();

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        if ($result->status != 1) {
            return response()->json(['error' => trans('toasts.quizNotProccesing')], 403);
        }

        // Update last heartbeat timestamp in cache
        $key = "heartbeat:{$this->studentId}:{$quiz->id}";
        Cache::put($key, now(), 120);

        return response()->json(['success' => true]);
    }

    public function review($uuid)
    {
        $quiz = Quiz::where('uuid', $uuid)
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            })
            ->withCount('questions')
            ->firstOrFail();

        if (now()->lessThan($quiz->end_time)) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.reviewNotAvailableYet'));
        }

        $quiz->total_score = $quiz->questions->flatMap(function ($question) {
            return $question->answers->pluck('score');
        })->sum();

        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        if ((!in_array($result->status, [2, 3])) || (!$quiz->show_result || !$quiz->allow_review)) {
            return redirect()->route('student.quizzes.index')->with('error', trans('toasts.reviewNotAvailable'));
        }

        $questions = StudentQuizOrder::where('student_quiz_order.student_id', $this->studentId)
            ->where('student_quiz_order.quiz_id', $quiz->id)
            ->with([
                'question' => function ($query) {
                    $query->select('id', 'question_text');
                },
                'question.answers' => function ($query) {
                    $query->select('id', 'question_id', 'answer_text', 'is_correct', 'score');
                }
            ])
            ->leftJoin('student_answers', function ($join) {
                $join->on('student_quiz_order.student_id', '=', 'student_answers.student_id')
                    ->on('student_quiz_order.quiz_id', '=', 'student_answers.quiz_id')
                    ->on('student_quiz_order.question_id', '=', 'student_answers.question_id');
            })
            ->select('student_quiz_order.question_id', 'student_quiz_order.display_order', 'student_quiz_order.answer_order', 'student_answers.answer_id')
            ->orderBy('display_order')
            ->get();

        $questions->each(function ($question) use ($quiz) {
            $question->sorted_answers = $quiz->randomize_answers && $question->answer_order
                ? collect(json_decode($question->answer_order, true))
                    ->map(function ($answerId) use ($question) {
                        return $question->question->answers->firstWhere('id', $answerId);
                    })
                    ->filter()
                    ->values()
                : $question->question->answers;
        });

        $correctAnswers = $questions->filter(function ($question) {
            return $question->answer_id && $question->question->answers->firstWhere('id', $question->answer_id)->is_correct;
        })->count();

        $wrongAnswers = $questions->filter(function ($question) {
            return $question->answer_id && !$question->question->answers->firstWhere('id', $question->answer_id)->is_correct;
        })->count();

        $unanswered = $questions->filter(function ($question) {
            return is_null($question->answer_id);
        })->count();

        $scores = StudentResult::where('quiz_id', $quiz->id)
            ->orderBy('total_score', 'desc')
            ->pluck('total_score')
            ->values()
            ->toArray();

        $totalStudents = count($scores);
        $uniqueScores = array_values(array_unique($scores));
        $rank = array_search($result->total_score, $uniqueScores) + 1;

        $lastRankScore = end($uniqueScores);
        $isLastRank = $result->total_score === $lastRankScore;

        $formattedRank = app()->getLocale() === 'ar'
            ? getArabicOrdinal($rank, $isLastRank)
            : ($isLastRank ? trans('admin/quizzes.lastRank') : $rank . (($rank % 10 == 1 && $rank % 100 != 11) ? 'st' : (($rank % 10 == 2 && $rank % 100 != 12) ? 'nd' : (($rank % 10 == 3 && $rank % 100 != 13) ? 'rd' : 'th'))));

        $prompt = str_replace(
            ['{name}', '{score}', '{total_score}', '{correct}', '{wrong}', '{unanswered}', '{rank}'],
            [$this->student->name, $result->total_score, $quiz->total_score, $correctAnswers, $wrongAnswers, $unanswered, $formattedRank],
            config('prompts.quiz_review')
        );
        $aiMessage = $this->geminiService->generateContent($prompt);

        return view('student.activities.quizzes.review', compact('quiz', 'result', 'questions', 'formattedRank', 'correctAnswers', 'wrongAnswers', 'unanswered', 'aiMessage'));
    }
}

