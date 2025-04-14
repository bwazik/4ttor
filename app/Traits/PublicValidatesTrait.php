<?php

namespace App\Traits;

use App\Models\Quiz;
use App\Models\Group;
use App\Models\Answer;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Question;
use App\Models\ZoomAccount;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Hash;

trait PublicValidatesTrait
{
    use ServiceResponseTrait;

    protected $questionsLimit = 50;
    protected $answersLimit = 5;

    protected function validateTeacherGrade($gradeId, $teacherId)
    {
        $teacherHasGrade = Teacher::where('id', $teacherId)
            ->whereHas('grades', fn($query) => $query->where('grades.id', $gradeId))
            ->exists();

        if (!$teacherHasGrade) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherGrade'));
        }

        return null;
    }

    protected function processPassword(array &$request): void
    {
        if (!empty($request['password'])) {
            $request['password'] = Hash::make($request['password']);
        } else {
            unset($request['password']);
        }
    }

    protected function validateSelectedItems(array $ids)
    {
        if (empty($ids)) {
            return $this->errorResponse(trans('main.noItemsSelected'));
        }

        return null;
    }

    protected function validateTeacherGradeAndGroups($teacherIds, $groupIds, $gradeId = null, $restrictToSingleTeacher = false)
    {
        $teacherIds = is_array($teacherIds) ? $teacherIds : [$teacherIds];

        if ($gradeId) {
            $teachersCount = Teacher::whereIn('id', $teacherIds)->count();
            $teachersWithGradeCount = Teacher::whereIn('id', $teacherIds)
                ->whereHas('grades', fn($query) => $query->where('grades.id', $gradeId))
                ->count();

            if ($teachersCount !== $teachersWithGradeCount) {
                return $this->errorResponse(trans('teacher/errors.validateTeacherGrade'));
            }
        }

        $query = Teacher::whereIn('id', $teacherIds)->with('groups');

        if ($restrictToSingleTeacher) {
            $query->where('id', $teacherIds[0]);
        }

        $teacherGroups = $query->get()
            ->pluck('groups')
            ->flatten()
            ->pluck('id')
            ->toArray();

        if ($gradeId) {
            $teacherGroups = array_filter($teacherGroups, function ($groupId) use ($gradeId) {
                return Group::where('id', $groupId)->where('grade_id', $gradeId)->exists();
            });
        }

        $invalidGroups = array_diff((array) $groupIds, $teacherGroups);

        if (!empty($invalidGroups)) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherGroups'));
        }

        return null;
    }

    protected function syncStudentParentRelation(array $newStudentIds, int $parentId, bool $isAdmin = false): void
    {
        $existingStudentsQuery = Student::where('parent_id', $parentId);

        if (!$isAdmin && isset($this->teacherId)) {
            $existingStudentsQuery->whereHas('teachers', fn($q) => $q->where('teacher_id', $this->teacherId));
        }

        $existingStudentIds = $existingStudentsQuery->pluck('id')->toArray();

        $removedStudentIds = array_diff($existingStudentIds, $newStudentIds);
        if (!empty($removedStudentIds)) {
            Student::whereIn('id', $removedStudentIds)->update(['parent_id' => null]);
        }

        $targetStudentQuery = Student::whereIn('id', $newStudentIds);
        if (!$isAdmin && isset($this->teacherId)) {
            $targetStudentQuery->whereHas('teachers', fn($q) => $q->where('teacher_id', $this->teacherId));
        }

        $validStudentIds = $targetStudentQuery->pluck('id')->toArray();

        if (!empty($validStudentIds)) {
            Student::whereIn('id', $validStudentIds)->update(['parent_id' => $parentId]);
        }
    }

    protected function verifyStudents(array $studentIds, int $gradeId, int $groupId)
    {
        $studentIds = array_unique($studentIds);

        $query = Student::whereIn('id', $studentIds)
            ->where('grade_id', $gradeId)
            ->whereHas('groups', fn($q) => $q->where('groups.id', $groupId));

        $validStudentCount = $query->count();

        $isValid = $validStudentCount === count($studentIds);

        if (!$isValid) {
            return $this->errorResponse(trans('admin/attendance.studentsNotValid'));
        }

        return null;
    }

    protected function hasZoomAccount(int $teacherId): bool
    {
        return ZoomAccount::where('teacher_id', $teacherId)->exists();
    }


    protected function configureZoomAPI(int $teacherId)
    {
        if (!$this->hasZoomAccount($teacherId)) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherZoomAccount'));
        }

        $zoomAccount = ZoomAccount::where('teacher_id', $teacherId)
            ->select('client_id', 'client_secret', 'account_id')
            ->first();

        config([
            'zoom.client_id' => $zoomAccount->client_id,
            'zoom.client_secret' => $zoomAccount->client_secret,
            'zoom.account_id' => $zoomAccount->account_id,
        ]);

        return true;
    }

    protected function ensureQuizOwnership($quizId, $teacherId)
    {
        $quiz = Quiz::where('id', $quizId)
                    ->where('teacher_id', $teacherId)
                    ->first();

        if (!$quiz) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherQuiz'));
        }

        return null;
    }

    protected function ensureQuestionOwnership($questionId, $teacherId)
    {
        $question = Question::where('id', $questionId)
                    ->whereHas('quiz', fn($query) => $query->where('teacher_id', $teacherId))
                    ->first();

        if (!$question) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherQuiz'));
        }

        return null;
    }

    protected function ensureQuestionLimitNotExceeded($quizId)
    {
        $questionCount = Question::where('quiz_id', $quizId)->count();

        if ($questionCount >= $this->questionsLimit) {
            return $this->errorResponse(trans('teacher/errors.quizHasMaxQuestions'));
        }

        return null;
    }

    protected function ensureAnswerLimitNotExceeded($questionId)
    {
        $answerCount = Answer::where('question_id', $questionId)->count();

        if ($answerCount >= $this->answersLimit) {
            return $this->errorResponse(trans('teacher/errors.questionHasMaxAnswers'));
        }

        return null;
    }
}
