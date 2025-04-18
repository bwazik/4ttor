<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\FileUploadService;
use App\Services\Teacher\Activities\AssignmentService;
use App\Http\Requests\Admin\Activities\AssignmentsRequest;

class AssignmentsController extends Controller
{
    use ValidatesExistence;

    protected $assignmentService;
    protected $fileUploadService;
    protected $teacherId;

    public function __construct(AssignmentService $assignmentService, FileUploadService $fileUploadService)
    {
        $this->assignmentService = $assignmentService;
        $this->fileUploadService = $fileUploadService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $assignmentsQuery = Assignment::query()
            ->select('id', 'uuid', 'grade_id', 'title', 'description', 'deadline', 'score')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->assignmentService->getAssignmentsForDatatable($assignmentsQuery);
        }

        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
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

        return view('teacher.activities.assignments.index', compact('grades', 'groups'));
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
        $id = Assignment::uuid($request->id)->value('id');

        $result = $this->assignmentService->updateAssignment($id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $id = Assignment::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'assignments');

        $result = $this->assignmentService->deleteAssignment($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $ids = Assignment::uuids($request->ids)->pluck('id')->toArray();
        $request->merge(['ids' => $ids]);

        $this->validateExistence($request, 'assignments');

        $result = $this->assignmentService->deleteSelectedAssignments($ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function details($uuid)
    {
        $assignment = Assignment::with(['grade', 'assignmentFiles', 'groups'])
            ->select('id', 'uuid', 'grade_id', 'title', 'description', 'deadline', 'score')
            ->uuid($uuid)
            ->firstOrFail();

        return view('teacher.activities.assignments.details', compact('assignment'));
    }

    public function uploadFile(Request $request, $uuid)
    {
        $id = Assignment::uuid($uuid)->value('id');

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
