<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Models\StudentFee;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Finance\StudentFeeService;
use App\Http\Requests\Admin\Finance\StudentFeesRequest;
use App\Models\Fee;
use App\Models\Student;

class StudentFeesController extends Controller
{
    use ValidatesExistence;

    protected $studentFeeService;

    public function __construct(StudentFeeService $studentFeeService)
    {
        $this->studentFeeService = $studentFeeService;
    }

    public function index(Request $request)
    {
        $studentFeesQuery = StudentFee::query()->with(['student', 'fee'])->select('id', 'student_id', 'fee_id', 'discount', 'is_exempted');

        if ($request->ajax()) {
            return $this->studentFeeService->getStudentFeesForDatatable($studentFeesQuery);
        }

        $students = Student::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $fees = Fee::query()->select('id', 'name', 'teacher_id', 'grade_id')
            ->with(['teacher:id,name', 'grade:id,name'])->orderBy('id')->get()
            ->mapWithKeys(function ($fee) {
                $teacherName = $fee->teacher->name ?? 'N/A';
                $gradeName = $fee->grade->name ?? 'N/A';
                return [$fee->id => $fee->name . ' - ' . $teacherName . ' - ' . $gradeName];
            });

        return view('admin.finance.studentFees.index', compact('students', 'fees'));
    }


    public function insert(StudentFeesRequest $request)
    {
        $result = $this->studentFeeService->insertStudentFee($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(StudentFeesRequest $request)
    {
        $result = $this->studentFeeService->updateStudentFee($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'student_fees');

        $result = $this->studentFeeService->deleteStudentFee($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'student_fees');

        $result = $this->studentFeeService->deleteSelectedStudentFees($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
