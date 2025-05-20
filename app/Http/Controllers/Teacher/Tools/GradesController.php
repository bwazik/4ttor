<?php

namespace App\Http\Controllers\Teacher\Tools;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Tools\GroupService;

class GradesController extends Controller
{
    use ValidatesExistence;

    protected $groupService;
    protected $teacherId;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index()
    {
        $grades = Grade::query()->select('id', 'name')->orderBy('id')
            ->whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->get();

        return view('teacher.tools.grades.index', compact('grades'));
    }

    public function getTeacherGroupsByGrade(Request $request, $gradeId)
    {
        $groupsQuery = Group::query()->with(['grade'])
            ->select('id', 'uuid', 'name', 'grade_id', 'day_1', 'day_2', 'time', 'is_active', 'created_at', 'updated_at')
            ->where('teacher_id', $this->teacherId)
            ->where('grade_id', $gradeId);

        if ($request->ajax()) {
            return $this->groupService->getTeacherGroupsByGradeForDatatable($groupsQuery);
        }
    }
}
