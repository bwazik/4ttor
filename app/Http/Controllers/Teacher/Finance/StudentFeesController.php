<?php

namespace App\Http\Controllers\Teacher\Finance;

use App\Models\StudentFee;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Finance\StudentFeeService;
use App\Http\Requests\Admin\Finance\StudentFeesRequest;
use App\Models\Fee;
use App\Models\Student;
use App\Traits\ServiceResponseTrait;

class StudentFeesController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $studentFeeService;
    protected $teacherId;

    public function __construct(StudentFeeService $studentFeeService)
    {
        $this->studentFeeService = $studentFeeService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $studentFeesQuery = StudentFee::query()->with(['student', 'fee'])
            ->select('id', 'uuid', 'student_id', 'fee_id', 'discount', 'is_exempted')
            ->whereHas('student', fn($query) => $query->whereHas('teachers', fn($q) => $q->where('teacher_id', $this->teacherId)))
            ->whereHas('fee', fn($query) => $query->where('teacher_id', $this->teacherId));

        if ($request->ajax()) {
            return $this->studentFeeService->getStudentFeesForDatatable($studentFeesQuery);
        }

        $students = Student::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'uuid', 'name')
            ->orderBy('id')
            ->pluck('name', 'uuid')
            ->toArray();

        $fees = Fee::query()->select('id', 'uuid', 'name', 'grade_id')
            ->where('teacher_id', $this->teacherId)
            ->with(['grade:id,name'])->orderBy('id')->get()
            ->mapWithKeys(function ($fee) {
                $gradeName = $fee->grade->name ?? 'N/A';
                return [$fee->uuid => $fee->name  . ' - ' . $gradeName];
            });

        return view('teacher.finance.studentFees.index', compact('students', 'fees'));
    }

    public function insert(StudentFeesRequest $request)
    {
        $result = $this->studentFeeService->insertStudentFee($request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function update(StudentFeesRequest $request)
    {
        $id = StudentFee::uuid($request->id)->value('id');

        $result = $this->studentFeeService->updateStudentFee($id, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function delete(Request $request)
    {
        $id = StudentFee::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'student_fees');

        $result = $this->studentFeeService->deleteStudentFee($request->id);

        return $this->conrtollerJsonResponse($result);
    }

    public function deleteSelected(Request $request)
    {
        $ids = StudentFee::whereIn('uuid', $request->ids ?? [])->pluck('id')->toArray();
        !empty($ids) ? $request->merge(['ids' => $ids]) : null;

        $this->validateExistence($request, 'student_fees');

        $result = $this->studentFeeService->deleteSelectedStudentFees($request->ids);

        return $this->conrtollerJsonResponse($result);
    }
}
