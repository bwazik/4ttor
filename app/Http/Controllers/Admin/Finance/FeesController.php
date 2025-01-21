<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Models\Fee;
use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Finance\FeeService;
use App\Http\Requests\Admin\Finance\FeesRequest;

class FeesController extends Controller
{
    use ValidatesExistence;

    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index(Request $request)
    {
        $feesQuery = Fee::query()->select('id', 'name', 'amount', 'teacher_id', 'grade_id', 'created_at');

        if ($request->ajax()) {
            return $this->feeService->getFeesForDatatable($feesQuery);
        }

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.finance.fees.index', compact('teachers', 'grades'));
    }

    public function insert(FeesRequest $request)
    {
        $result = $this->feeService->insertFee($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(FeesRequest $request)
    {
        $result = $this->feeService->updateFee($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'fees');

        $result = $this->feeService->deleteFee($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'fees');

        $result = $this->feeService->deleteSelectedFees($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
