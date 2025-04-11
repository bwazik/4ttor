<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Grade;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Activities\AttendanceService;
use App\Http\Requests\Admin\Activities\AttendanceRequest;
use App\Http\Requests\Admin\Activities\StudentSearchRequest;

class AttendanceController extends Controller
{
    protected $attendanceService;
    protected $teacherId;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index()
    {
        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        return view('teacher.activities.attendance.index', compact('grades'));
    }

    public function getStudentsByFilter(StudentSearchRequest $request)
    {
        $result = $this->attendanceService->getStudentsByFilter($request->validated());

        if ($request->ajax()) {
            if ($result instanceof \Illuminate\Http\JsonResponse || $result instanceof \Yajra\DataTables\DataTableAbstract) {
                return $result;
            }

            if (isset($result['status']) && $result['status'] === 'error') {
                return response()->json(['error' => $result['message']], 500);
            }
        }

        return response()->json(['error' => trans('main.errorMessage')], 500);
    }


    public function insert(AttendanceRequest $request)
    {
        $result = $this->attendanceService->insertAttendance($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
