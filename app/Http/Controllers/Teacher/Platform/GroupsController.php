<?php

namespace App\Http\Controllers\Teacher\Platform;

use App\Models\Grade;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\GroupsRequest;
use App\Services\Teacher\Platform\GroupService;

class GroupsController extends Controller
{
    use ValidatesExistence;

    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {
        $teacherId = Auth::id();

        $groupsQuery = Group::query()
            ->select('id', 'name', 'grade_id', 'day_1', 'day_2', 'time', 'is_active', 'created_at', 'updated_at')
            ->where('teacher_id', $teacherId);

        if ($request->ajax()) {
            return $this->groupService->getGroupsForDatatable($groupsQuery);
        }

        $pageStatistics = [
            'totalGroups' => Group::where('teacher_id', $teacherId)->count(),
            'activeGroups' => Group::where('teacher_id', $teacherId)->active()->count(),
            'inactiveGroups' => Group::where('teacher_id', $teacherId)->inactive()->count(),
            'topGrade' => Group::where('teacher_id', $teacherId)
                ->select('grade_id', DB::raw('COUNT(*) as group_count'))
                ->groupBy('grade_id')
                ->orderByDesc('group_count')
                ->with('grade:id,name')
                ->first()
        ];
        
        $grades = Grade::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('teacher.platform.groups.index', compact('grades', 'pageStatistics'));
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
}
