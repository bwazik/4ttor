<?php

namespace App\Http\Controllers\Admin\Activities;

use App\Models\Teacher;
use App\Http\Controllers\Controller;
use App\Services\Admin\Activities\AttendanceService;
use App\Http\Requests\Admin\Activities\StudentSearchRequest;
use App\Http\Requests\Admin\Activities\AttendanceRequest;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.activities.attendance.index', compact('teachers'));
    }

    public function getStudentsByFilter(StudentSearchRequest $request)
    {
        if ($request->ajax()) {
            return $this->attendanceService->getStudentsByFilter($request->validated());
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
