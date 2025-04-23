<?php

namespace App\Http\Controllers\Teacher\Users;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Student;
use App\Models\MyParent;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StudentsRequest;
use App\Services\Teacher\Users\StudentService;

class StudentsController extends Controller
{
    use ValidatesExistence;

    protected $studentService;
    protected $teacherId;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $studentsQuery = Student::query()
            ->select('id', 'username', 'name', 'phone', 'email', 'birth_date', 'gender', 'grade_id', 'parent_id', 'is_active', 'profile_pic')
            ->whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId));

        if ($request->ajax()) {
            return $this->studentService->getStudentsForDatatable($studentsQuery);
        }

        $baseStatsQuery = Student::whereHas('teachers', fn($q) => $q->where('teacher_id', $this->teacherId));

        $pageStatistics = [
            'totalStudents' => (clone $baseStatsQuery)->count(),
            'activeStudents' => (clone $baseStatsQuery)->active()->count(),
            'inactiveStudents' => (clone $baseStatsQuery)->inactive()->count(),
            'archivedStudents' => (clone $baseStatsQuery)->onlyTrashed()->count(),
            // 'exemptedStudents' => (clone $baseStatsQuery)->exempted()->count(),
            // 'discountedStudents' => (clone $baseStatsQuery)->where('fees_discount', '>', 0)->count(),
            'topGrade' => (clone $baseStatsQuery)->select('grade_id', DB::raw('COUNT(*) as student_count'))
                ->groupBy('grade_id')
                ->orderByDesc('student_count')
                ->with('grade:id,name')
                ->first(),
        ];

        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        $parents = MyParent::whereHas('students.teachers', fn($query) => $query->where('teachers.id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        $groups = Group::query()
            ->select('id', 'name', 'grade_id')
            ->where('teacher_id', $this->teacherId)
            ->with('grade:id,name')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(fn($group) => [$group->id => $group->name . ' - ' . $group->grade->name]);

        return view('teacher.users.students.index', compact('pageStatistics', 'grades', 'parents', 'groups'));
    }

    public function insert(StudentsRequest $request)
    {
        $result = $this->studentService->insertStudent($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(StudentsRequest $request)
    {
        $result = $this->studentService->updateStudent($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'students');

        $result = $this->studentService->deleteStudent($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'students');

        $result = $this->studentService->deleteSelectedStudents($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
