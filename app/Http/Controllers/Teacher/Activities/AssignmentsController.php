<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use App\Services\Admin\FileUploadService;
use App\Services\Teacher\Activities\AssignmentService;
use App\Http\Requests\Admin\Activities\AssignmentsRequest;

class AssignmentsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

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
        $assignmentsQuery = Assignment::query()->with(['grade:id,name'])
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
            ->select('id', 'uuid', 'name', 'grade_id')
            ->where('teacher_id', $this->teacherId)
            ->with('grade:id,name')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(fn($group) => [$group->uuid => $group->name . ' - ' . $group->grade->name]);

        return view('teacher.activities.assignments.index', compact('grades', 'groups'));
    }

    public function insert(AssignmentsRequest $request)
    {
        $result = $this->assignmentService->insertAssignment($request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function update(AssignmentsRequest $request)
    {
        $id = Assignment::uuid($request->id)->value('id');

        $result = $this->assignmentService->updateAssignment($id, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function delete(Request $request)
    {
        $id = Assignment::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'assignments');

        $result = $this->assignmentService->deleteAssignment($request->id);

        return $this->conrtollerJsonResponse($result);
    }

    public function deleteSelected(Request $request)
    {
        $ids = Assignment::whereIn('uuid', $request->ids ?? [])->pluck('id')->toArray();
        !empty($ids) ? $request->merge(['ids' => $ids]) : null;

        $this->validateExistence($request, 'assignments');

        $result = $this->assignmentService->deleteSelectedAssignments($request->ids);

        return $this->conrtollerJsonResponse($result);
    }

    public function details($uuid)
    {
        $assignment = Assignment::with(['grade', 'assignmentFiles', 'groups'])
            ->select('id', 'uuid', 'grade_id', 'title', 'description', 'deadline', 'score')
            ->uuid($uuid)
            ->where('teacher_id', $this->teacherId)
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

        return $this->conrtollerJsonResponse($result);
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

        return $this->conrtollerJsonResponse($result);
    }
}
