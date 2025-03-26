<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Traits\ServiceResponseTrait;

trait PublicValidatesTrait
{
    use ServiceResponseTrait;

    protected function validateTeacherGrade($gradeId)
    {
        $teacherHasGrade = Teacher::where('id', Auth::id())
            ->whereHas('grades', fn($query) => $query->where('grades.id', $gradeId))
            ->exists();

        if (!$teacherHasGrade) {
            return $this->errorResponse(trans('teacher/errors.validateTeacherGroup'));
        }

        return null;
    }
}
