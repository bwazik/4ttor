<?php

namespace App\Http\Controllers\Admin\Students;

use App\Models\Teacher;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\StudentService;
use App\Http\Requests\Admin\StudentsRequest;
use App\Models\MyParent;

class StudentsController extends Controller
{
    use ValidatesExistence;

    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $studentsQuery = Student::query()->select('id', 'username', 'name', 'phone', 'email', 'birth_date', 'gender', 'grade_id', 'parent_id', 'is_active', 'profile_pic');

        if ($request->ajax()) {
            return $this->studentService->getStudentsForDatatable($studentsQuery);
        }

        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $parents = MyParent::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.students.manage.index', compact('teachers', 'grades', 'parents'));
    }

    public function archived(Request $request)
    {
        $studentsQuery = Student::query()->onlyTrashed()->select('id', 'username', 'name', 'phone', 'grade_id', 'profile_pic');

        if ($request->ajax()) {
            return $this->studentService->getArchivedStudentsForDatatable($studentsQuery);
        }

        return view('admin.students.archive.index');
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

    public function archive(Request $request)
    {
        $this->validateExistence($request, 'students');

        $result = $this->studentService->archiveStudent($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restore(Request $request)
    {
        $this->validateExistence($request, 'students');

        $result = $this->studentService->restoreStudent($request->id);

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

    public function archiveSelected(Request $request)
    {
        $this->validateExistence($request, 'students');

        $result = $this->studentService->archiveSelectedStudents($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restoreSelected(Request $request)
    {
        $this->validateExistence($request, 'students');

        $result = $this->studentService->restoreSelectedStudents($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
