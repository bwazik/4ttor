<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Traits\PublicValidatesTrait;

class DataFetchController extends Controller
{
    use PublicValidatesTrait;

    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = auth('teacher')->check() ? auth('teacher')->id() : null;
    }


    public function getTeacherGroupsByGrade(...$args)
    {
        $isAdminContext = isAdmin() ? true : false;

        try {
            if ($isAdminContext) {
                [$teacherId, $gradeId] = $args;
                $effectiveTeacherId = $teacherId;
            } else {
                [$gradeId] = $args;
                $effectiveTeacherId = $this->teacherId;
            }

            if ($validationResult = $this->validateTeacherGrade($gradeId, $effectiveTeacherId)) {
                return $validationResult;
            }

            $query = Group::select('id', 'name', 'grade_id')
                ->where('teacher_id', $effectiveTeacherId)
                ->where('grade_id', $gradeId)
                ->with('grade:id,name');

            $groups = $query->orderBy($isAdminContext ? 'id' : 'grade_id')
                ->get()
                ->mapWithKeys(function ($group) {
                    $gradeName = $group->grade?->name ?? 'Unknown Grade';
                    return [$group->id => "{$group->name} - {$gradeName}"];
            });

            if ($groups->isEmpty()) {
                return $this->errorResponse(trans('teacher/errors.noGroupsForGrade'));
            }

            return response()->json(['status' => 'success', 'data' => $groups]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }
}
