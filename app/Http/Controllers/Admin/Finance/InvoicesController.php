<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Models\Invoice;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Services\Admin\Finance\InvoiceService;
use App\Http\Requests\Admin\Finance\InvoicesRequest;
use App\Models\Fee;
use App\Models\Plan;
use App\Models\Student;

class InvoicesController extends Controller
{
    use ValidatesExistence;

    protected $invoiceService;
    protected const TYPE_MAPPING = [
        'teachers' => [
            'relation_id' => 'teacher_id',
            'type' => 'plan_id',
            'relation_model' => Teacher::class,
            'type_model' => Plan::class,
            'view' => 'admin.finance.invoices.teachers.index'
        ],
        'students' => [
            'relation_id' => 'student_id',
            'type' => 'fee_id',
            'relation_model' => Student::class,
            'type_model' => Fee::class,
            'view' => 'admin.finance.invoices.students.index'
        ]
    ];

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $type = $this->getTypeFromRequest();

        if (!isset(self::TYPE_MAPPING[$type])) {
            abort(404);
        }

        $mapping = self::TYPE_MAPPING[$type];

        $invoicesQuery = Invoice::query()->select(['id', 'date', 'amount', $mapping['type'], $mapping['relation_id'], 'created_at'])->whereNotNull($mapping['relation_id']);;

        if ($request->ajax()) {
            return $this->invoiceService->getInvoicesForDatatable($invoicesQuery, $type);
        }

        $relations = $mapping['relation_model']::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $types = $mapping['type_model']::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        $relationKey = Str::plural(strtolower(class_basename($mapping['relation_model'])));
        $typeKey = Str::plural(strtolower(class_basename($mapping['type_model'])));

        return view($mapping['view'], [$relationKey => $relations, $typeKey => $types]);
    }

    public function insert(InvoicesRequest $request)
    {
        $type = $this->getTypeFromRequest();
        $request = $request->merge(['type' => $type])->toArray();

        $result = $this->invoiceService->insertInvoice($request);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'invoices');

        $result = $this->invoiceService->deleteInvoice($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    private function getTypeFromRequest()
    {
        return $this->invoiceService->getTypeFromRequest();
    }
}
