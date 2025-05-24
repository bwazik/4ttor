<?php

namespace App\Services\Student\Activities;

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
            ->addColumn('status', fn($row) => $this->getQuizStatus($row))
            ->filterColumn('teacher_id', fn($query, $keyword) => filterByRelation($query, 'teacher', 'name', $keyword))
            ->rawColumns(['startQuiz', 'status'])
            ->make(true);
    }

    protected function getQuizLink($row)
    {
        $isAvailable = now()->between($row->start_time, $row->end_time);
        return formatSpanUrl(
            $isAvailable ? route('student.quizzes.notices', $row->uuid) : '#',
            $isAvailable ? trans('admin/quizzes.startQuiz') : trans('admin/quizzes.notAvailable'),
            $isAvailable ? 'success' : 'secondary',
            false
        );
    }

    protected function getQuizStatus($quiz)
    {
        $result = StudentResult::where('student_id', $this->studentId)
            ->where('quiz_id', $quiz->id)
            ->first();

        if (!$result) {
            return '<span class="badge bg-label-info">' . trans('admin/quizzes.notStarted') . '</span>';
        }

        return match ($result->status) {
            1 => '<span class="badge rounded-pill bg-label-primary text-capitalized">' . trans('admin/quizzes.inProgress') . '</span>',
            2 => '<span class="badge rounded-pill bg-label-success text-capitalized">' . trans('admin/quizzes.completed') . '</span>',
            3 => '<span class="badge rounded-pill bg-label-danger text-capitalized">' . trans('admin/quizzes.failed') . '</span>',
            default => '<span class="badge rounded-pill bg-label-warning text-capitalized">N/A</span>',
        };
    }

    public function initializeQuizOrder(StudentResult $result, Quiz $quiz)
    {
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
    }

    public function updateStudentResult(StudentResult $result)
    {
        $answers = StudentAnswer::where('student_id', $result->student_id)
            ->where('quiz_id', $result->quiz_id)
            ->with(['answer' => fn($q) => $q->select('id', 'score', 'is_correct')])
            ->get();

        $totalScore = $answers->sum(fn($answer) => $answer->answer->is_correct ? $answer->answer->score : 0);
        $maxScore = $result->quiz->questions()->with('answers')->get()->sum(fn($q) => $q->answers->where('is_correct', true)->max('score'));

        $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

        $result->update([
            'total_score' => $totalScore,
            'percentage' => round($percentage, 2),
        ]);
    }

    public function ensureQuizOwnership($quizId, $teacherId)
    {
        $quiz = Quiz::where('id', $quizId)
                    ->where('teacher_id', $teacherId)
                    ->first();

        if (!$quiz) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherQuiz'));
        }

        return null;
    }

    public function ensureQuestionOwnership($questionId, $teacherId)
    {
        $question = Question::where('id', $questionId)
                    ->whereHas('quiz', fn($query) => $query->where('teacher_id', $teacherId))
                    ->first();

        if (!$question) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherQuiz'));
        }

        return null;
    }

}
