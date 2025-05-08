<?php

namespace App\Http\Controllers\Teacher\Tools;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Tools\GroupService;
use App\Services\Teacher\Tools\LessonService;
use App\Http\Requests\Admin\Tools\GroupsRequest;

class GroupsController extends Controller
{
    use ValidatesExistence;

    protected $groupService;
    protected $lessonService;
    protected $teacherId;

    public function __construct(GroupService $groupService, LessonService $lessonService)
    {
        $this->groupService = $groupService;
        $this->lessonService = $lessonService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $groupsQuery = Group::query()->with(['grade'])
            ->select('id', 'uuid', 'name', 'grade_id', 'day_1', 'day_2', 'time', 'is_active', 'created_at', 'updated_at')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->groupService->getGroupsForDatatable($groupsQuery);
        }

        $baseStatsQuery = Group::where('teacher_id', $this->teacherId);

        $pageStatistics = [
            'totalGroups' => (clone $baseStatsQuery)->count(),
            'activeGroups' => (clone $baseStatsQuery)->active()->count(),
            'inactiveGroups' => (clone $baseStatsQuery)->inactive()->count(),
            'topGrade' => (clone $baseStatsQuery)->select('grade_id', DB::raw('COUNT(*) as group_count'))
                ->groupBy('grade_id')
                ->orderByDesc('group_count')
                ->with('grade:id,name')
                ->first(),
        ];

        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        return view('teacher.tools.groups.index', compact('grades', 'pageStatistics'));
    }

    public function insert(GroupsRequest $request)
    {
        $result = $this->groupService->insertGroup($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(GroupsRequest $request)
    {
        $result = $this->groupService->updateGroup($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'groups');

        $result = $this->groupService->deleteGroup($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'groups');

        $result = $this->groupService->deleteSelectedGroups($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function lessons(Request $request, $uuid)
    {
        $group = Group::select('id', 'uuid', 'name', 'teacher_id')
            ->uuid($uuid)
            ->firstOrFail();

        $lessonsQuery = Lesson::query()->with(['group'])
            ->select('id', 'uuid', 'title', 'group_id', 'date', 'time', 'status')
            ->where('group_id', $group->id);

        if ($request->ajax()) {
            return $this->lessonService->getLessonsForDatatable($lessonsQuery);
        }

        return view('teacher.tools.groups.lessons', compact('group'));
    }
}
