<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\GroupService;
use App\Http\Requests\Admin\Tools\GroupsRequest;

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
        $groupsQuery = Group::query()->select('id', 'name', 'teacher_id', 'grade_id', 'day_1', 'day_2', 'time', 'is_active', 'created_at', 'updated_at');

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

    public function getGroupStudents($id)
    {
        try {
            Group::select('id')->findOrFail($id);

            $students = Student::whereHas('groups', function ($query) use ($id) {
                $query->where('group_id', $id);
            })
                ->select('id', 'name')
                ->orderBy('id')
                ->get()
                ->mapWithKeys(fn($student) => [$student->id => $student->name]);

            if ($students->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('main.noStudentsAssigned'),
                ], 404);
            }

            return response()->json(['status' => 'success', 'data' => $students]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ], 500);
        }

    }
}
