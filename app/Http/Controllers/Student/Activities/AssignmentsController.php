<?php

namespace App\Http\Controllers\Student\Activities;

use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Models\SubmissionFile;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Admin\FileUploadService;

class AssignmentsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $fileUploadService;
    protected $student;
    protected $studentId;
    protected $studentGradeId;
    protected $studentGroupIds;
    protected $teacherIds;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->student = auth()->guard('student')->user();
        $this->studentId = $this->student->id;
        $this->studentGradeId = $this->student->grade_id;
        $this->studentGroupIds = Cache::remember("student_groups:{$this->studentId}", now()->addHours(24), function () {
            return $this->student->groups()->pluck('groups.id')->toArray();
        });
        $this->teacherIds = Cache::remember("student_teachers:{$this->studentId}", now()->addHours(24), function () {
            return $this->student->teachers()->pluck('teachers.id')->toArray();
        });
    }

    public function index(Request $request)
    {
        $assignmentsQuery = $this->getStudentAssignmentQuery()
            ->select('id', 'uuid', 'teacher_id', 'title', 'deadline', 'score');

        if ($request->ajax()) {
            return $this->getAssignmentsForDatatable($assignmentsQuery);
        }

        return view('student.activities.assignments.index');
    }

    protected function getAssignmentsForDatatable($assignmentsQuery)
    {
        return datatables()->eloquent($assignmentsQuery)
            ->addIndexColumn()
            ->editColumn('title', fn($row) => $row->title)
            ->editColumn('teacher_id', fn($row) => formatRelation($row->teacher_id, $row->teacher, 'name'))
            ->editColumn('deadline', fn($row) => isoFormat($row->deadline))
            ->addColumn('actions', fn($row) => $this->getAssignmentLink($row))
            ->filterColumn('teacher_id', fn($query, $keyword) => filterByRelation($query, 'teacher', 'name', $keyword))
            ->rawColumns(['teacher_id', 'actions'])
            ->make(true);
    }

    protected function getAssignmentLink($row)
    {
        return formatSpanUrl(
            route('student.assignments.details', $row->uuid),
            trans('main.details'),
            'info',
            false
        );
    }

    public function details($uuid)
    {
        $assignment = $this->getStudentAssignmentQuery()
            ->with([
                'teacher',
                'grade',
                'assignmentFiles',
                'groups',
                'assignmentSubmissions' => function ($query) {
                    $query->where('student_id', $this->studentId)->with('submissionFiles');
                }
            ])
            ->select('id', 'uuid', 'teacher_id', 'grade_id', 'title', 'description', 'deadline', 'score')
            ->uuid($uuid)->firstOrFail();

        return view('student.activities.assignments.details', compact('assignment'));
    }

    public function uploadFile(Request $request, $uuid)
    {
        $assignment = $this->getStudentAssignmentQuery()->uuid($uuid)->select('id', 'deadline')->first();

        if (!$assignment) {
            return response()->json(['error' => trans('toasts.ownershipError')], 403);
        }

        if ($assignment->deadline && now()->gt($assignment->deadline)) {
            return response()->json(['error' => trans('toasts.assignmentDeadlinePassed')], 403);
        }

        $request->validate([
            'file' => 'required|file|max:6144|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png'
        ]);

        $result = $this->fileUploadService->uploadFile($request, 'submission', $assignment->id);

        return $this->conrtollerJsonResponse($result,
        [
            "student_assignment_review:{$this->studentId}:{$assignment->id}",
            "assignment_{$assignment->id}_avg_files",
            "assignment_{$assignment->id}_avg_file_size",
        ]);
    }

    public function downloadAssignment($fileId)
    {
        $result = $this->fileUploadService->downloadFile('assignment', $fileId);

        if ($result instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            return $result;
        }

        abort(404);
    }

    public function downloadFile($fileId)
    {
        $result = $this->fileUploadService->downloadFile('submission', $fileId);

        if ($result instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            return $result;
        }

        abort(404);
    }

    public function deleteFile(Request $request)
    {
        $this->validateExistence($request, 'submission_files');

        $file = SubmissionFile::where('id', $request->id)
            ->whereHas('submission', fn($query) => $query->where('student_id', $this->studentId))
            ->with('submission:id,assignment_id', 'submission.assignment:id,deadline')
            ->first(['id', 'submission_id']);

        if (!$file) {
            return response()->json(['error' => trans('toasts.ownershipError')], 403);
        }

        $assignment = $file->submission->assignment;

        if ($assignment->deadline && now()->gt($assignment->deadline)) {
            return response()->json(['error' => trans('toasts.assignmentDeadlinePassed')], 403);
        }

        $result = $this->fileUploadService->deleteFile('submission', $request->id);

        return $this->conrtollerJsonResponse($result,
        [
            "student_assignment_review:{$this->studentId}:{$assignment->id}",
            "assignment_{$assignment->id}_avg_files",
            "assignment_{$assignment->id}_avg_file_size",
        ]);
    }

    # Helpers
    protected function getStudentAssignmentQuery()
    {
        return Assignment::query()
            ->where('grade_id', $this->studentGradeId)
            ->whereIn('teacher_id', $this->teacherIds)
            ->whereHas('groups', function ($query) {
                $query->whereIn('groups.id', $this->studentGroupIds);
            });
    }
}
