<?php

namespace App\Services\Admin\Finance;

use App\Models\Receipt;
use App\Models\TeacherAccount;
use App\Models\StudentAccount;
use App\Services\Admin\StudentService;
use App\Services\Admin\TeacherService;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class ReceiptService
{
    use PreventDeletionIfRelated;

    protected $studentService, $teacherService;
    protected $relationships = ['studentAccount', 'teacherAccount'];
    protected $transModelKey = 'admin/receipts.receipts';
    protected const TYPE_CONFIG = [
        'teachers' => [
            'relation' => 'teacher',
            'account_model' => TeacherAccount::class
        ],
        'students' => [
            'relation' => 'student',
            'account_model' => StudentAccount::class
        ]
    ];

    public function __construct(StudentService $studentService, TeacherService $teacherService)
    {
        $this->studentService = $studentService;
        $this->teacherService = $teacherService;
    }

    public function getReceiptsForDatatable($receiptsQuery, string $type)
    {
        $config = self::TYPE_CONFIG[$type];

        return datatables()->eloquent($receiptsQuery)
            ->addIndexColumn()
            ->editColumn($config['relation'] . '_id', function ($row) use ($config) {
                $relation = $config['relation'];
                return "<a target='_blank' href='" . route("admin.{$relation}s.details", $row->{$relation . '_id'}) . "'>" .
                    ($row->{$relation . '_id'} ? $row->{$relation}->name : '-') .
                    "</a>";
            })
            ->editColumn('debit', function ($row) {
                return formatCurrency($row->debit) . ' ' . trans('main.currency');
            })
            ->editColumn('description', function ($row) {
                return $row->description ?: '-';
            })
            ->addColumn('actions', function ($row) use ($config) {
                return $this->generateActionButtons($row, $config);
            })
            ->rawColumns(['actions', $config['relation'] . '_id'])
            ->make(true);
    }

    public function insertReceipt(array $request)
    {
        DB::beginTransaction();

        try {
            $type = $request['type'];
            $mapping = self::TYPE_CONFIG[$type] ?? null;

            if (!$mapping) {
                throw new \InvalidArgumentException('Invalid receipt type');
            }

            $relationId = $mapping['relation'] . '_id';

            $receiptData = [
                'date' => date('Y-m-d'),
                $relationId => $request[$relationId] ?? null,
                'debit' => $request['amount'] ?? 0.00,
                'description' => $request['description'],
            ];

            // Remove any null values
            $receiptData = array_filter($receiptData, fn($value) => !is_null($value));

            $receipt = Receipt::create($receiptData);

            $this->createAccountTransaction($mapping, $receipt, $request[$relationId]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/receipts.receipt')]),
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

    public function updateReceipt(int $id, array $request)
    {
        DB::beginTransaction();

        try {
            $type = $request['type'];
            $mapping = self::TYPE_CONFIG[$type] ?? null;

            if (!$mapping) {
                throw new \InvalidArgumentException('Invalid receipt type');
            }

            $receipt = Receipt::findOrFail($id);

            $receiptData = [
                'debit' => $request['amount'] ?? 0.00,
                'description' => $request['description'],
            ];

            $receipt->update($receiptData);

            $this->updateAccountTransaction($mapping, $receipt);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/receipts.receipt')]),
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

    public function deleteReceipt($id): array
    {
        DB::beginTransaction();

        try {
            $receipt = Receipt::select('id', 'debit')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($receipt)) {
                return $dependencyCheck;
            }

            $receipt->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/receipts.receipt')]),
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

    protected function generateActionButtons($row, $config)
    {
        $relationId = $config['relation'];

        $accountBalance = $relationId === 'student'
            ? number_format($this->studentService->getStudentAccountBalance($row->student_id), 2)
            : number_format($this->teacherService->getTeacherAccountBalance($row->teacher_id), 2);

        return
        '<div class="align-items-center">
            <span class="text-nowrap">
                <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                    tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal"
                    id="edit-button" data-id="' . $row->id . '" data-' . $relationId . '_id="' . $row->{$relationId}->name . '"
                    data-account_balance="' . $accountBalance . '" data-amount="' . $row->debit . '" data-description="' . $row->description . '">
                    <i class="ri-edit-box-line ri-20px"></i>
                </button>
            </span>
            <button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                id="delete-button"
                data-id="' . $row->id . '"
                data-amount="' . $row->debit . '"
                data-' . $relationId . '_id="' . $row->{$relationId}->name . '"
                data-bs-target="#delete-modal"
                data-bs-toggle="modal"
                data-bs-dismiss="modal">
                <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
            </button>
        </div>';
    }

    public function getTypeFromRequest()
    {
        $segments = request()->segments();

        $actions = ['insert', 'update', 'delete'];

        if (in_array(last($segments), $actions)) {
            $type = $segments[count($segments) - 2] ?? null;
        } else {
            $type = last($segments);
        }

        if (!$type) {
            abort(404);
        }

        return $type;
    }

    protected function createAccountTransaction($mapping, $receipt, $relationId)
    {
        $accountModel = $mapping['account_model'];

        $accountModel::create([
            'type' => 2, // 1 - Invoice, 2 - Receipt, 3 - Refund
            $mapping['relation'] . '_id' => $relationId,
            'receipt_id' => $receipt->id,
            'debit' => 0.00,
            'credit' => $receipt->debit,
        ]);
    }

    protected function updateAccountTransaction($mapping, $receipt)
    {
        $accountModel = $mapping['account_model'];
        $account = $accountModel::where('receipt_id', $receipt->id)->firstOrFail();

        if (!$account) {
            throw new \Exception('Account record not found for this receipt');
        }

        $account->update([
            'debit' => 0.00,
            'credit' => $receipt->debit,
            'description' => $receipt->description,
        ]);
    }

    public function checkDependenciesForSingleDeletion($teacher): array|null
    {
        return $this->checkForSingleDependencies($teacher, $this->relationships, $this->transModelKey);
    }
}
