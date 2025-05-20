<?php

namespace App\Http\Controllers\Teacher\Finance;

use App\Models\Fee;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Finance\FeeService;
use App\Http\Requests\Admin\Finance\FeesRequest;
use App\Traits\ServiceResponseTrait;

class FeesController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $feeService;
    protected $teacherId;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $feesQuery = Fee::query()->with(['grade:id,name'])
            ->select('id', 'uuid', 'name', 'amount', 'grade_id', 'frequency')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->feeService->getFeesForDatatable($feesQuery);
        }

        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        return view('teacher.finance.fees.index', compact('grades'));
    }


    public function insert(FeesRequest $request)
    {
        $result = $this->feeService->insertFee($request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function update(FeesRequest $request)
    {
        $id = Fee::uuid($request->id)->value('id');

        $result = $this->feeService->updateFee($id, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function delete(Request $request)
    {
        $id = Fee::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'fees');

        $result = $this->feeService->deleteFee($request->id);

        return $this->conrtollerJsonResponse($result);
    }

    public function deleteSelected(Request $request)
    {
        $ids = Fee::whereIn('uuid', $request->ids ?? [])->pluck('id')->toArray();
        !empty($ids) ? $request->merge(['ids' => $ids]) : null;

        $this->validateExistence($request, 'fees');

        $result = $this->feeService->deleteSelectedFees($request->ids);

        return $this->conrtollerJsonResponse($result);
    }

}
