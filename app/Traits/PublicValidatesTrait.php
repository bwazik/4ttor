<?php

namespace App\Traits;

use App\Models\Group;
use App\Models\Student;
use App\Models\Teacher;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

trait PublicValidatesTrait
{
    use ServiceResponseTrait;

    protected function validateTeacherGrade($gradeId)
    {
        $teacherHasGrade = Teacher::where('id', Auth::id())
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

    public function validateTeacherGradeAndGroups($teacherIds, $groupIds, $gradeId = null, $restrictToSingleTeacher = false)
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

    private function syncStudentParentRelation(array $newStudentIds, int $parentId, bool $isAdmin = false): void
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

}
