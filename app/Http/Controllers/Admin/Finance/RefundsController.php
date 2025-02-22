<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Models\Refund;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Services\Admin\Finance\RefundService;
use App\Http\Requests\Admin\Finance\RefundsRequest;
use App\Models\Student;

class RefundsController extends Controller
{
    use ValidatesExistence;

    protected $refundService;
    protected const TYPE_MAPPING = [
        'teachers' => [
            'relation_id' => 'teacher_id',
            'relation_model' => Teacher::class,
            'view' => 'admin.finance.refunds.teachers.index'
        ],
        'students' => [
            'relation_id' => 'student_id',
            'relation_model' => Student::class,
            'view' => 'admin.finance.refunds.students.index'
        ]
    ];

    public function __construct(RefundService $refundService)
    {
        $this->refundService = $refundService;
    }

    public function index(Request $request)
    {
        $type = $this->getTypeFromRequest();

        if (!isset(self::TYPE_MAPPING[$type])) {
            abort(404);
        }

        $mapping = self::TYPE_MAPPING[$type];

        $refundsQuery = Refund::query()->select(['id', 'date', $mapping['relation_id'], 'debit', 'description', 'created_at', 'updated_at'])->whereNotNull($mapping['relation_id']);;

        if ($request->ajax()) {
            return $this->refundService->getRefundsForDatatable($refundsQuery, $type);
        }

        $relations = $mapping['relation_model']::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $relationKey = Str::plural(strtolower(class_basename($mapping['relation_model'])));

        return view($mapping['view'], [$relationKey => $relations]);
    }

    public function insert(RefundsRequest $request)
    {
        $type = $this->getTypeFromRequest();
        $request = $request->merge(['type' => $type])->toArray();

        $result = $this->refundService->insertRefund($request);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(RefundsRequest $request)
    {
        $type = $this->getTypeFromRequest();
        $request = $request->merge(['type' => $type])->toArray();

        $result = $this->refundService->updateRefund($request['id'], $request);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'refunds');

        $result = $this->refundService->deleteRefund($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    private function getTypeFromRequest()
    {
        return $this->refundService->getTypeFromRequest();
    }
}
