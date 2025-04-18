<?php

namespace App\Http\Controllers\Admin\Activities;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\FileUploadService;
use App\Services\Admin\Activities\AssignmentService;
use App\Http\Requests\Admin\Activities\AssignmentsRequest;

class AssignmentsController extends Controller
{
    use ValidatesExistence;

    protected $assignmentService;
    protected $fileUploadService;

    public function __construct(AssignmentService $assignmentService, FileUploadService $fileUploadService)
    {
        $this->assignmentService = $assignmentService;
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request)
    {
        $assignmentsQuery = Assignment::query()
            ->select('id', 'teacher_id', 'grade_id', 'title', 'description', 'deadline', 'score');

        if ($request->ajax()) {
            return $this->assignmentService->getAssignmentsForDatatable($assignmentsQuery);
        }

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $groups = Group::query()->select('id', 'name', 'teacher_id', 'grade_id')
            ->with(['teacher:id,name', 'grade:id,name'])->orderBy('id')->get()
            ->mapWithKeys(function ($group) {
                $gradeName = $group->grade->name ?? 'N/A';
                $teacherName = $group->teacher->name ?? 'N/A';
                return [$group->id => $group->name . ' - ' . $gradeName . ' - ' . $teacherName];
            });

        return view('admin.activities.assignments.index', compact('teachers', 'grades', 'groups'));
    }

    public function insert(AssignmentsRequest $request)
    {
        $result = $this->assignmentService->insertAssignment($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(AssignmentsRequest $request)
    {
        $result = $this->assignmentService->updateAssignment($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'assignments');

        $result = $this->assignmentService->deleteAssignment($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'assignments');

        $result = $this->assignmentService->deleteSelectedAssignments($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function details($id)
    {
        $assignment = Assignment::with(['teacher', 'grade', 'assignmentFiles', 'groups'])
            ->select('id', 'teacher_id', 'grade_id', 'title', 'description', 'deadline', 'score')
            ->findOrFail($id);

        return view('admin.activities.assignments.details', compact('assignment'));
    }

    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:6144|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png'
        ]);

        $result = $this->fileUploadService->uploadFile($request, 'assignment', $id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function downloadFile($fileId)
    {
        $result = $this->fileUploadService->downloadFile('assignment', $fileId);

        if ($result instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            return $result;
        }

        abort(404);
    }

    public function deleteFile(Request $request)
    {
        $this->validateExistence($request, 'assignment_files');

        $result = $this->fileUploadService->deleteFile('assignment', $request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
