<?php

namespace App\Services\Admin\Activities;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class AttendanceService
{
    use PreventDeletionIfRelated;

    public function getStudentsByFilter(array $request)
    {
        $studentsQuery = Student::query()
            ->select('students.id', 'students.name', 'attendances.status', 'attendances.note')
            ->join('student_teacher', 'students.id', '=', 'student_teacher.student_id')
            ->join('student_group', 'students.id', '=', 'student_group.student_id')
            ->leftJoin('attendances', function ($join) use ($request) {
                $join->on('students.id', '=', 'attendances.student_id')
                    ->where('attendances.teacher_id', '=', $request['teacher_id'])
                    ->where('attendances.date', '=', $request['date']);
            })
            ->where('student_teacher.teacher_id', $request['teacher_id'])
            ->where('students.grade_id', $request['grade_id'])
            ->where('student_group.group_id', $request['group_id']);

        return datatables()->eloquent($studentsQuery)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('note', function ($row) {
                return $this->generateNoteCell($row);
            })
            ->addColumn('actions', function ($row) {
                return $this->generateActionsCell($row);
            })
            ->rawColumns(['selectbox', 'note', 'actions'])
            ->make(true);
    }

    private function generateActionsCell($student): string
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

    private function generateNoteCell($student): string
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
        DB::beginTransaction();

        try {
            $teacherId = $request['teacher_id'];
            $gradeId = $request['grade_id'];
            $groupId = $request['group_id'];
            $date = $request['date'];
            $attendanceData = $request['attendance'];

            if (!$this->verifyTeacherAuthorization($teacherId, $gradeId, $groupId)) {
                return [
                    'status' => 'error',
                    'message' => trans('admin/attendance.teacherNotAuthorized'),
                ];
            }

            $studentIds = collect($attendanceData)->pluck('student_id')->toArray();
            if (!$this->verifyStudents($studentIds, $gradeId, $groupId)) {
                return [
                    'status' => 'error',
                    'message' => trans('admin/attendance.studentsNotValid'),
                ];
            }

            foreach ($attendanceData as $entry) {

                Attendance::updateOrCreate(
                    [
                        'student_id' => $entry['student_id'],
                        'date' => $date,
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

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/attendance.attendance')]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    private function verifyTeacherAuthorization(int $teacherId, int $gradeId, int $groupId): bool
    {
        return Teacher::where('id', $teacherId)
            ->whereHas('students', function($query) use ($gradeId, $groupId) {
                $query->where('grade_id', $gradeId)
                    ->whereHas('groups', function($q) use ($groupId) {
                        $q->where('groups.id', $groupId);
                    });
            })
            ->exists();
    }

    private function verifyStudents(array $studentIds, int $gradeId, int $groupId): bool
    {
        $validStudentCount = Student::whereIn('id', $studentIds)
            ->where('grade_id', $gradeId)
            ->whereHas('groups', function($query) use ($groupId) {
                $query->where('groups.id', $groupId);
            })
            ->count();

        return $validStudentCount === count($studentIds);
    }
}
