<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Traits\PublicValidatesTrait;
use Illuminate\Support\Facades\Auth;


class DataFetchController extends Controller
{
    use PublicValidatesTrait;

    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = Auth::id();
    }


    public function getTeacherGroupsByGrade($grade)
    {
        if ($validationResult = $this->validateTeacherGrade($grade))
            return $validationResult;

        try {
            $groups = Group::select('id', 'name', 'grade_id')
            ->where('teacher_id', $this->teacherId)
            ->where('grade_id', $grade)
            ->with('grade:id,name')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(fn($group) => [$group->id => $group->name . ' - ' . $group->grade->name]);

            if ($groups->isEmpty()) {
                return $this->errorResponse(trans('teacher/errors.noGroupsForGrade'));
            }

            return response()->json(['status' => 'success', 'data' => $groups]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }
}
