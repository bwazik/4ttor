<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\GroupService;
use App\Services\Admin\Tools\LessonService;
use App\Http\Requests\Admin\Tools\GroupsRequest;

class GroupsController extends Controller
{
    use ValidatesExistence;

    protected $groupService;
    protected $lessonService;

    public function __construct(GroupService $groupService, LessonService $lessonService)
    {
        $this->groupService = $groupService;
        $this->lessonService = $lessonService;
    }

    public function index(Request $request)
    {
        $groupsQuery = Group::query()->with(['teacher', 'grade'])
            ->select('id', 'name', 'teacher_id', 'grade_id', 'day_1', 'day_2', 'time', 'is_active', 'created_at', 'updated_at');

        if ($request->ajax()) {
            return $this->groupService->getGroupsForDatatable($groupsQuery);
        }

        $pageStatistics = [
            'totalGroups' => Group::count(),
            'activeGroups' => Group::active()->count(),
            'inactiveGroups' => Group::inactive()->count(),
            'topGrade' => Group::select('grade_id', DB::raw('COUNT(*) as group_count'))
                ->groupBy('grade_id')
                ->orderByDesc('group_count')
                ->with('grade:id,name')
                ->first()
        ];

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.tools.groups.index', compact('teachers', 'grades', 'pageStatistics'));
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

    public function lessons(Request $request, $groupId)
    {
        $group = Group::with(['teacher:id,name'])
            ->select('id', 'name', 'teacher_id')
            ->findOrFail($groupId);

        $lessonsQuery = Lesson::query()->with(['group'])
            ->select('id', 'title', 'group_id', 'date', 'time', 'status')
            ->where('group_id', $groupId);

        if ($request->ajax()) {
            return $this->lessonService->getLessonsForDatatable($lessonsQuery);
        }

        return view('admin.tools.groups.lessons', compact('group'));
    }
}
