<?php

namespace App\Services\Student\Activities;

use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\StudentResult;
use App\Models\StudentQuizOrder;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class QuizService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $studentId;

    public function __construct()
    {
        $this->studentId = auth()->guard('student')->user()->id;
    }

    public function getQuizzesForDatatable($quizzesQuery)
    {
        return datatables()->eloquent($quizzesQuery)
            ->addIndexColumn()
            ->addColumn('startQuiz', fn($row) => $this->getQuizLink($row))
            ->editColumn('name', fn($row) => $row->name)
            ->editColumn('teacher_id', fn($row) => formatRelation($row->teacher_id, $row->teacher, 'name'))
            ->addColumn('duration', fn($row) => formatDuration($row->duration))
            ->editColumn('start_time', fn($row) => isoFormat($row->start_time))
            ->editColumn('end_time', fn($row) => isoFormat($row->end_time))
            ->filterColumn('teacher_id', fn($query, $keyword) => filterByRelation($query, 'teacher', 'name', $keyword))
            ->rawColumns(['startQuiz', 'status'])
            ->make(true);
    }

    protected function getQuizLink($row)
    {
        $isWithinTimeWindow = now()->between($row->start_time, $row->end_time);
        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $row->id)
            ->first();

        if ($result && ($result->status == 2 || $result->status == 3) && ($row->show_result || $row->allow_review) && now()->greaterThanOrEqualTo($row->end_time)) {
            return formatSpanUrl(
                route('student.quizzes.review', $row->uuid),
                trans('admin/quizzes.reviewAnswers'),
                'info',
                false
            );
        }

        // Check availability for starting/resuming
        $linkText = trans('admin/quizzes.notAvailable');
        $linkColor = 'secondary';
        $linkUrl = '#';

        if ($isWithinTimeWindow) {
            if (!$result || $result->status == 1) {
                $linkText = $result ? trans('admin/quizzes.resumeQuiz') : trans('admin/quizzes.startQuiz');
                $linkColor = 'success';
                $linkUrl = route('student.quizzes.notices', $row->uuid);
            }
        } elseif ($row->quiz_mode == 2 && $result && $result->status == 1) {
            $endTime = Carbon::parse($result->started_at)->addMinutes($row->duration);
            if (now()->lessThan($endTime)) {
                $linkText = trans('admin/quizzes.resumeQuiz');
                $linkColor = 'success';
                $linkUrl = route('student.quizzes.notices', $row->uuid);
            }
        }

        return formatSpanUrl($linkUrl, $linkText, $linkColor, false);
    }

    protected function getQuizStatus($quiz)
    {
        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->first();

        if (!$result) {
            return '<span class="badge rounded-pill bg-label-info text-capitalized">' . trans('admin/quizzes.notStarted') . '</span>';
        }

        return match ($result->status) {
            1 => '<span class="badge rounded-pill bg-label-warning text-capitalized">' . trans('admin/quizzes.inProgress') . '</span>',
            2 => '<span class="badge rounded-pill bg-label-success text-capitalized">' . trans('admin/quizzes.completed') . '</span>',
            3 => '<span class="badge rounded-pill bg-label-danger text-capitalized">' . trans('admin/quizzes.failed') . '</span>',
            default => '<span class="badge rounded-pill bg-label-warning text-capitalized">N/A</span>',
        };
    }

    public function initializeQuizOrder(StudentResult $result, Quiz $quiz)
    {
        return $this->executeTransaction(function () use ($result, $quiz) {
            if (
                StudentQuizOrder::where('student_id', $result->student_id)
                    ->where('quiz_id', $quiz->id)
                    ->exists()
            ) {
                return;
            }

            $questions = $quiz->questions()->get();
            if ($questions->isEmpty()) {
                return;
            }

            $questions = $quiz->randomize_questions ? $questions->shuffle() : $questions;

            foreach ($questions as $index => $question) {
                $answerOrder = null;
                if ($quiz->randomize_answers) {
                    $answers = $question->answers()->pluck('id')->toArray();
                    shuffle($answers);
                    $answerOrder = json_encode($answers);
                } else {
                    $answerOrder = json_encode($question->answers()->pluck('id')->toArray());
                }

                StudentQuizOrder::create([
                    'student_id' => $result->student_id,
                    'quiz_id' => $quiz->id,
                    'question_id' => $question->id,
                    'display_order' => $index + 1,
                    'answer_order' => $answerOrder,
                ]);
            }
        });

    }

    public function updateStudentResult(StudentResult $result)
    {
        return $this->executeTransaction(function () use ($result) {
            $answers = StudentAnswer::where('student_id', $result->student_id)
                ->where('quiz_id', $result->quiz_id)
                ->with(['answer' => fn($q) => $q->select('id', 'score', 'is_correct')])
                ->get();

            $totalScore = $answers->sum(fn($answer) => $answer->answer && $answer->answer->is_correct ? $answer->answer->score : 0);
            $maxScore = $result->quiz->questions()->with('answers')->get()->sum(fn($q) => $q->answers->where('is_correct', true)->max('score'));

            $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

            $result->update([
                'total_score' => $totalScore,
                'percentage' => round($percentage, 2),
            ]);
        });
    }
}
