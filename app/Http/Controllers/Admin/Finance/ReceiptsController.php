<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Models\Receipt;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Services\Admin\Finance\ReceiptService;
use App\Http\Requests\Admin\Finance\ReceiptsRequest;
use App\Models\Student;

class ReceiptsController extends Controller
{
    use ValidatesExistence;

    protected $receiptService;
    protected const TYPE_MAPPING = [
        'teachers' => [
            'relation_id' => 'teacher_id',
            'relation_model' => Teacher::class,
            'view' => 'admin.finance.receipts.teachers.index'
        ],
        'students' => [
            'relation_id' => 'student_id',
            'relation_model' => Student::class,
            'view' => 'admin.finance.receipts.students.index'
        ]
    ];

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    public function index(Request $request)
    {
        $type = $this->getTypeFromRequest();

        if (!isset(self::TYPE_MAPPING[$type])) {
            abort(404);
        }

        $mapping = self::TYPE_MAPPING[$type];

        $receiptsQuery = Receipt::query()->select(['id', 'date', $mapping['relation_id'], 'debit', 'description', 'created_at', 'updated_at'])->whereNotNull($mapping['relation_id']);;

        if ($request->ajax()) {
            return $this->receiptService->getReceiptsForDatatable($receiptsQuery, $type);
        }

        $relations = $mapping['relation_model']::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $relationKey = Str::plural(strtolower(class_basename($mapping['relation_model'])));

        return view($mapping['view'], [$relationKey => $relations]);
    }

    public function insert(ReceiptsRequest $request)
    {
        $type = $this->getTypeFromRequest();
        $request = $request->merge(['type' => $type])->toArray();

        $result = $this->receiptService->insertReceipt($request);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(ReceiptsRequest $request)
    {
        $type = $this->getTypeFromRequest();
        $request = $request->merge(['type' => $type])->toArray();

        $result = $this->receiptService->updateReceipt($request['id'], $request);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'receipts');

        $result = $this->receiptService->deleteReceipt($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    private function getTypeFromRequest()
    {
        return $this->receiptService->getTypeFromRequest();
    }
}
