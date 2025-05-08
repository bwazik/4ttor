<?php

namespace App\Services\Admin\Activities;

use App\Models\Lesson;
use App\Models\Student;
use App\Models\Attendance;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class AttendanceService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    public function getStudentsByFilter(array $request)
    {
        $lesson = Lesson::select('id', 'date')->findOrFail($request['lesson_id']);

        if ($validationResult = $this->validateTeacherGradeAndGroups($request['teacher_id'], $request['group_id'], $request['grade_id'], true))
            return $validationResult;

        $studentsQuery = Student::query()
            ->select('students.id', 'students.name', 'attendances.status', 'attendances.note')
            ->join('student_teacher', 'students.id', '=', 'student_teacher.student_id')
            ->join('student_group', 'students.id', '=', 'student_group.student_id')
            ->leftJoin('attendances', function ($join) use ($request, $lesson) {
                $join->on('students.id', '=', 'attendances.student_id')
                    ->where('attendances.teacher_id', '=', $request['teacher_id'])
                    ->where('attendances.lesson_id', '=', $request['lesson_id'])
                    ->where('attendances.date', '=', $lesson->date);
            })
            ->where('student_teacher.teacher_id', $request['teacher_id'])
            ->where('students.grade_id', $request['grade_id'])
            ->where('student_group.group_id', $request['group_id']);

        return datatables()->eloquent($studentsQuery)
            ->editColumn('name', fn($row) => $row->name)
            ->addColumn('note', fn($row) => $this->generateNoteCell($row))
            ->addColumn('actions', fn($row) => $this->generateActionsCell($row))
            ->rawColumns(['selectbox', 'note', 'actions'])
            ->make(true);
    }

    public function generateActionsCell($student): string
    {
        $statuses = [
            1 => ['color' => 'success', 'label' => trans('admin/attendance.p')],
            2 => ['color' => 'danger',  'label' => trans('admin/attendance.a')],
            3 => ['color' => 'warning', 'label' => trans('admin/attendance.l')],
            4 => ['color' => 'info',    'label' => trans('admin/attendance.e')]
        ];

        $html = '<div class="status-container" data-student-id="' . $student->id . '">';
        foreach ($statuses as $status => $config) {
            $isActive = $student->status == $status ? 'active' : '';
            $html .= sprintf(
                '<button type="button"
                    class="btn btn-outline-%s btn-sm status-btn mx-1 %s"
                    data-status="%d">
                    <span class="status-indicator">%s</span>
                </button>',
                $config['color'],
                $isActive,
                $status,
                $config['label']
            );
        }
        $html .= '</div>';
        return $html;
    }

    public function generateNoteCell($student): string
    {
        return sprintf(
            '<input type="text" id="note_%d" class="form-control form-control-sm note-input"
             name="note" placeholder="%s" data-student-id="%d" value="%s">',
            $student->id,
            trans('main.description'),
            $student->id,
            $student->note ?? ''
        );
    }

    public function insertAttendance(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            $teacherId = $request['teacher_id'];
            $gradeId = $request['grade_id'];
            $groupId = $request['group_id'];
            $lesson = Lesson::select('id', 'date')->findOrFail($request['lesson_id']);
            $attendanceData = $request['attendance'];

            if ($validationResult = $this->validateTeacherGradeAndGroups($teacherId, $groupId, $gradeId, true))
                return $validationResult;

            if ($lesson->date !== now()->toDateString()) {
                return $this->errorResponse(trans('admin/attendance.dateRestriction'));
            }

            $studentIds = collect($attendanceData)->pluck('student_id')->toArray();

            if ($validationResult2 = $this->verifyStudents($studentIds, $gradeId, $groupId))
                return $validationResult2;

            foreach ($attendanceData as $entry) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $entry['student_id'],
                        'date' => $lesson->date,
                        'lesson_id' => $lesson->id,
                        'teacher_id' => $teacherId,
                    ],
                    [
                        'grade_id' => $gradeId,
                        'group_id' => $groupId,
                        'status' => $entry['status'],
                        'note' => $entry['note'] ?? null,
                    ]
                );
            }

            return $this->successResponse(trans('main.added', ['item' => trans('admin/attendance.attendance')]));
        });
    }
}
