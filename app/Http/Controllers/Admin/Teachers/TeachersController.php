<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Models\Fee;
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
use App\Models\TeacherAccount;

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

    // public function groups($ids)
    // {
    //     try {
    //         $teacherIdsArray = explode(',', $ids);

    //         $validTeachers = Teacher::whereIn('id', $teacherIdsArray)->pluck('id')->toArray();

    //         if (empty($validTeachers)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => trans('main.noTeachersFound'),
    //             ], 404);
    //         }

    //         $groups = Group::whereIn('teacher_id', $validTeachers)
    //             ->select('id', 'name', 'teacher_id')
    //             ->with('teacher:id,name')
    //             ->orderBy('id')
    //             ->get()
    //             ->mapWithKeys(fn($group) => [$group->id => $group->name . ' - ' . $group->teacher->name]);

    //         if ($groups->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => trans('main.noGroupsAssigned'),
    //             ], 404);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $groups,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => config('app.env') === 'production'
    //                 ? trans('main.errorMessage')
    //                 : $e->getMessage(),
    //         ], 500);
    //     }
    // }

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

        return response()->json(['status' => 'success', 'data' => $groups]);
    }

    public function grades($id)
    {
        try {
            $teacher = Teacher::select('id')->findOrFail($id);

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

    public function getTeacherFees($id)
    {
        try {
            $teacher = Teacher::select('id')->findOrFail($id);

            $fees = Fee::whereHas('teacher', function ($query) use ($id) {
                $query->where('teacher_id', $id);
            })
            ->select('id', 'name', 'grade_id')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(fn($fee) => [$fee->id => $fee->name . ' - ' . $fee->grade->name]);

            if ($fees->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('main.noFeesAssigned'),
                ], 404);
            }

            return response()->json(['status' => 'success', 'data' => $fees]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ], 500);
        }
    }

    public function getTeacherAccountBalance($id)
    {
        try {
            $balance = $this->teacherService->getTeacherAccountBalance($id);

            return response()->json([
                'status' => 'success',
                'data' => number_format($balance, 2),
            ]);
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
