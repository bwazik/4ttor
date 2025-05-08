<?php

namespace App\Http\Controllers\Admin\Users\Teachers;

use App\Models\Fee;
use App\Models\Plan;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\GroupService;
use App\Services\Admin\Users\TeacherService;
use App\Http\Requests\Admin\Users\TeachersRequest;

class TeachersController extends Controller
{
    use ValidatesExistence;

    protected $teacherService;
    protected $groupService;

    public function __construct(TeacherService $teacherService, GroupService $groupService)
    {
        $this->teacherService = $teacherService;
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {
        $teachersQuery = Teacher::query()->select('id', 'username', 'name', 'phone', 'email', 'subject_id', 'plan_id', 'is_active', 'profile_pic');

        if ($request->ajax()) {
            return $this->teacherService->getTeachersForDatatable($teachersQuery);
        }

        $pageStatistics = [
            'totalTeachers' => Teacher::count(),
            'activeTeachers' => Teacher::active()->count(),
            'inactiveTeachers' => Teacher::inactive()->count(),
            'archivedTeachers' => Teacher::onlyTrashed()->count(),
            'topSubject' => Teacher::select('subject_id', DB::raw('COUNT(*) as teacher_count'))
            ->groupBy('subject_id')
            ->orderByDesc('teacher_count')
            ->with('subject:id,name')
            ->first()
        ];

        $plans = Plan::active()->select('id', 'name', 'description', 'monthly_price')->orderBy('id')->get();
        $subjects = Subject::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.users.teachers.manage.index', compact('pageStatistics', 'plans', 'subjects', 'grades'));
    }

    public function archived(Request $request)
    {
        $teachersQuery = Teacher::query()->onlyTrashed()->select('id', 'username', 'name', 'phone', 'email', 'profile_pic');

        if ($request->ajax()) {
            return $this->teacherService->getArchivedTeachersForDatatable($teachersQuery);
        }

        return view('admin.users.teachers.archive.index');
    }

    public function insert(TeachersRequest $request)
    {
        $result = $this->teacherService->insertTeacher($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(TeachersRequest $request)
    {
        $result = $this->teacherService->updateTeacher($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'teachers');

        $result = $this->teacherService->deleteTeacher($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function archive(Request $request)
    {
        $this->validateExistence($request, 'teachers');

        $result = $this->teacherService->archiveTeacher($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restore(Request $request)
    {
        $this->validateExistence($request, 'teachers');

        $result = $this->teacherService->restoreTeacher($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'teachers');

        $result = $this->teacherService->deleteSelectedTeachers($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function archiveSelected(Request $request)
    {
        $this->validateExistence($request, 'teachers');

        $result = $this->teacherService->archiveSelectedTeachers($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restoreSelected(Request $request)
    {
        $this->validateExistence($request, 'teachers');

        $result = $this->teacherService->restoreSelectedTeachers($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function grades($teacherId)
    {
        $teacher = Teacher::select('id', 'name')->findOrFail($teacherId);

        $grades = Grade::query()->select('id', 'name')->orderBy('id')
            ->whereHas('teachers', fn($query) => $query->where('teacher_id', $teacherId))
            ->get();

        return view('admin.users.teachers.manage.grades', compact('teacher', 'grades'));
    }


    public function getTeacherGroupsByGrade(Request $request, $teacherId, $gradeId)
    {
        $groupsQuery = Group::query()->with(['grade'])
            ->select('id', 'name', 'grade_id', 'day_1', 'day_2', 'time', 'is_active', 'created_at', 'updated_at')
            ->where('teacher_id', $teacherId)
            ->where('grade_id', $gradeId);

        if ($request->ajax()) {
            return $this->groupService->getTeacherGroupsByGradeForDatatable($groupsQuery);
        }
    }

    public function getTeacherGroups(Request $request)
    {
        $validated = $request->validate([
            'teachers' => 'required|array',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $teacherIds = $validated['teachers'];

        $groups = Group::whereIn('teacher_id', $teacherIds)
            ->select('id', 'name', 'teacher_id', 'grade_id')
            ->with('teacher:id,name', 'grade:id,name')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(function ($group) {
                $gradeName = $group->grade->name ?? 'N/A';
                $teacherName = $group->teacher->name ?? 'N/A';
                return [$group->id => $group->name . ' - ' . $gradeName . ' - ' . $teacherName];
            });

        return response()->json(['status' => 'success', 'data' => $groups]);
    }

    public function getTeacherGrades($id)
    {
        try {
            Teacher::select('id')->findOrFail($id);

            $grades = Grade::whereHas('teachers', function ($query) use ($id) {
                $query->where('teacher_id', $id);
            })
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(fn($grade) => [$grade->id => $grade->name]);

            if ($grades->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('main.noGradesAssigned'),
                ], 404);
            }

            return response()->json(['status' => 'success', 'data' => $grades]);
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
