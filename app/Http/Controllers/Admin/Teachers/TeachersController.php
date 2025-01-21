<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Models\Plan;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\TeacherService;
use App\Http\Requests\Admin\TeachersRequest;

class TeachersController extends Controller
{
    use ValidatesExistence;

    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index(Request $request)
    {
        $teachersQuery = Teacher::query()->select('id', 'username', 'name', 'phone', 'email', 'subject_id', 'plan_id', 'is_active', 'profile_pic');

        if ($request->ajax()) {
            return $this->teacherService->getTeachersForDatatable($teachersQuery);
        }

        $plans = Plan::active()->select('id', 'name', 'description', 'monthly_price')->orderBy('id')->get();
        $subjects = Subject::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.teachers.manage.index', compact('plans', 'subjects', 'grades'));
    }

    public function archived(Request $request)
    {
        $teachersQuery = Teacher::query()->onlyTrashed()->select('id', 'username', 'name', 'phone', 'subject_id', 'profile_pic');

        if ($request->ajax()) {
            return $this->teacherService->getArchivedTeachersForDatatable($teachersQuery);
        }

        return view('admin.teachers.archive.index');
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

    public function groups(Request $request)
    {
        $validated = $request->validate([
            'teachers' => 'required|array',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $teacherIds = $validated['teachers'];

        $groups = Group::whereIn('teacher_id', $teacherIds)->select('id', 'name', 'teacher_id')
            ->with('teacher:id,name')->orderBy('id')->get()
            ->mapWithKeys(function ($group) {
                return [$group->id => $group->name . ' - ' . $group->teacher->name];
            });

        return response()->json($groups);
    }

    public function grades(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $teacherId = $validated['teacher_id'];

        $grades = Grade::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
            })
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(function ($grade) {
                return [$grade->id => $grade->name];
            });
            
        return response()->json($grades);
    }
}
