<?php

namespace App\Services\Teacher\Activities;

use App\Models\Group;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\SubmissionFile;
use App\Models\AssignmentSubmission;
use App\Traits\PublicValidatesTrait;
use Illuminate\Support\Facades\Cache;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;
use App\Services\Admin\FileUploadService;

class AssignmentService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $teacherId;
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function getAssignmentsForDatatable($assignmentsQuery)
    {
        return datatables()->eloquent($assignmentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->uuid))
            ->editColumn('title', fn($row) => $row->title)
            ->editColumn('grade_id', fn($row) => formatRelation($row->grade_id, $row->grade, 'name'))
            ->editColumn('deadline', fn($row) => isoFormat($row->deadline))
            ->editColumn('description', fn($row) => $row->description ?: '-')
            ->addColumn('actions', fn($row) => $this->generateActionButtons($row))
            ->filterColumn('grade_id', fn($query, $keyword) => filterByRelation($query, 'grade', 'name', $keyword))
            ->rawColumns(['selectbox', 'actions'])
            ->make(true);
    }

    private function generateActionButtons($row)
    {
        $groupIds = $row->groups->pluck('uuid')->toArray();
        $groups = implode(',', $groupIds);

        return
            '<div class="d-inline-block">' .
            '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' .
            '<i class="ri-more-2-line"></i>' .
            '</a>' .
            '<ul class="dropdown-menu dropdown-menu-end m-0">' .
            '<li>
                        <a href="' . route('teacher.assignments.reports', $row->uuid) . '" class="dropdown-item">' . trans('main.reports') . '</a>
                    </li>' .
            '<li>
                        <a target="_blank" href="' . route('teacher.assignments.details', $row->uuid) . '" class="dropdown-item">' . trans('main.details') . '</a>
                    </li>' .
            '<div class="dropdown-divider"></div>' .
            '<li>' .
            '<a href="javascript:;" class="dropdown-item text-danger" ' .
            'id="delete-button" ' .
            'data-id="' . $row->uuid . '" ' .
            'data-title_ar="' . $row->getTranslation('title', 'ar') . '" ' .
            'data-title_en="' . $row->getTranslation('title', 'en') . '" ' .
            'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
            trans('main.delete') .
            '</a>' .
            '</li>' .
            '</ul>' .
            '</div>' .
            '<button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light" ' .
            'tabindex="0" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" ' .
            'id="edit-button" ' .
            'data-id="' . $row->uuid . '" ' .
            'data-title_ar="' . $row->getTranslation('title', 'ar') . '" ' .
            'data-title_en="' . $row->getTranslation('title', 'en') . '" ' .
            'data-grade_id="' . $row->grade_id . '" ' .
            'data-groups="' . $groups . '" ' .
            'data-deadline="' . humanFormat($row->deadline) . '" ' .
            'data-score="' . $row->score . '" ' .
            'data-description="' . $row->description . '">' .
            '<i class="ri-edit-box-line ri-20px"></i>' .
            '</button>';
    }

    public function insertAssignment(array $request)
    {
        return $this->executeTransaction(function () use ($request) {
            $groupIds = Group::whereIn('uuid', $request['groups'])->pluck('id')->toArray();

            if ($validationResult = $this->validateTeacherGradeAndGroups($this->teacherId, $groupIds, $request['grade_id'], true))
                return $validationResult;

            $assignment = Assignment::create([
                'teacher_id' => $this->teacherId,
                'grade_id' => $request['grade_id'],
                'title' => ['en' => $request['title_en'], 'ar' => $request['title_ar']],
                'deadline' => $request['deadline'],
                'score' => $request['score'],
                'description' => $request['description'],
            ]);

            $assignment->groups()->attach($groupIds);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/assignments.assignment')]));
        }, trans('toasts.ownershipError'));
    }

    public function updateAssignment($id, array $request): array
    {
        return $this->executeTransaction(function () use ($id, $request) {
            $groupIds = Group::whereIn('uuid', $request['groups'])->pluck('id')->toArray();

            if ($validationResult = $this->validateTeacherGradeAndGroups($this->teacherId, $groupIds, $request['grade_id'], true))
                return $validationResult;

            $assignment = Assignment::where('teacher_id', $this->teacherId)->findOrFail($id);
            $assignment->update([
                'grade_id' => $request['grade_id'],
                'title' => ['en' => $request['title_en'], 'ar' => $request['title_ar']],
                'deadline' => $request['deadline'],
                'score' => $request['score'],
                'description' => $request['description'],
            ]);

            $assignment->groups()->sync($groupIds ?? []);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/assignments.assignment')]));
        }, trans('toasts.ownershipError'));
    }

    public function deleteAssignment($id): array
    {
        return $this->executeTransaction(function () use ($id) {
            $assignment = Assignment::where('teacher_id', $this->teacherId)->findOrFail($id);

            $this->fileUploadService->deleteRelatedFiles($assignment, 'assignmentFiles');
            $this->fileUploadService->deleteRelatedFiles($assignment, 'assignmentSubmissions');

            $assignment->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/assignments.assignment')]));
        }, trans('toasts.ownershipError'));
    }

    public function deleteSelectedAssignments($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids) {
            $assignments = Assignment::where('teacher_id', $this->teacherId)->whereIn('id', $ids)->get();

            foreach ($assignments as $assignment) {
                $this->fileUploadService->deleteRelatedFiles($assignment, 'assignmentFiles');
                $this->fileUploadService->deleteRelatedFiles($assignment, 'assignmentSubmissions');

                $assignment->delete();
            }

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/assignments.assignments')]));
        }, trans('toasts.ownershipError'));
    }

    public function feedback($uuid, $studentUuid, array $request): array
    {
        return $this->executeTransaction(function () use ($uuid, $studentUuid, $request)
        {
            $assignment = Assignment::where('teacher_id', $this->teacherId)
                ->uuid($uuid)
                ->firstOrFail();

            $student = Student::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
                ->uuid($studentUuid)
                ->firstOrFail();

            $submission = AssignmentSubmission::where('student_id', $student->id)
                ->where('assignment_id', $assignment->id)
                ->firstOrFail();

            if($request['score'] > $assignment->score) {
                return $this->errorResponse(trans('toasts.invalidScore'));
            }

            $submission->update([
                'score' => $request['score'],
                'feedback' => $request['feedback'],
            ]);

            Cache::forget("student_assignment_review:{$student->id}:{$assignment->id}");
            Cache::forget("assignment_{$assignment->id}_avg_score");
            Cache::forget("score_distribution_{$assignment->id}");
            Cache::forget("top_students_{$assignment->id}");

            return $this->successResponse(trans('toasts.feedbackSubmitted'));
        }, trans('toasts.ownershipError'));
    }

    public function resetStudentAssignment($uuid, $studentUuid): array
    {
        return $this->executeTransaction(function () use ($uuid, $studentUuid)
        {
            $assignment = Assignment::where('teacher_id', $this->teacherId)
                ->uuid($uuid)
                ->select('id')
                ->firstOrFail();

            $student = Student::where('uuid', $studentUuid)
                ->whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
                ->select('id')
                ->firstOrFail();

            $submission = AssignmentSubmission::where('student_id', $student->id)
                ->where('assignment_id', $assignment->id)
                ->first();
            
            if ($submission) {
                $this->fileUploadService->deleteRelatedFiles($submission, 'submissionFiles');
                $submission->delete();
            }

            Cache::forget("student_assignment_review:{$student->id}:{$assignment->id}");
            Cache::forget("assignment_{$assignment->id}_avg_score");
            Cache::forget("assignment_{$assignment->id}_avg_files");
            Cache::forget("assignment_{$assignment->id}_avg_file_size");
            Cache::forget("score_distribution_{$assignment->id}");
            Cache::forget("top_students_{$assignment->id}");

            return $this->successResponse(trans('toasts.assignmentResetSuccess'));
        }, trans('toasts.ownershipError'));
    }
}
