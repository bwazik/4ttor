<?php

namespace App\Services\Admin\Finance;

use App\Models\Fee;
use App\Models\Plan;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\TeacherAccount;
use App\Models\StudentAccount;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class InvoiceService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['studentAccount', 'teacherAccount'];
    protected $transModelKey = 'admin/invoices.invoices';
    protected const TYPE_CONFIG = [
        'teachers' => [
            'relation' => 'teacher',
            'type' => 'plan',
            'account_model' => TeacherAccount::class
        ],
        'students' => [
            'relation' => 'student',
            'type' => 'fee',
            'account_model' => StudentAccount::class
        ]
    ];

    public function getInvoicesForDatatable($invoicesQuery, string $type)
    {
        $config = self::TYPE_CONFIG[$type];

        return datatables()->eloquent($invoicesQuery)
            ->addIndexColumn()
            ->editColumn('amount', function ($row) {
                return formatCurrency($row->amount) . ' ' . trans('main.currency');
            })
            ->editColumn($config['relation'] . '_id', function ($row) use ($config) {
                $relation = $config['relation'];
                return "<a target='_blank' href='" . route("admin.{$relation}s.details", $row->{$relation . '_id'}) . "'>" .
                    ($row->{$relation . '_id'} ? $row->{$relation}->name : '-') .
                    "</a>";
            })
            ->editColumn($config['type'] . '_id', function ($row) use ($config) {
                $relation = $config['type'];

                if ($relation === 'fee') {
                    return $row->fee_id
                        ? $row->fee->name . " - <a target='_blank' href='" . route('admin.teachers.details', $row->fee->teacher_id) . "'>" .
                        $row->fee->teacher->name . "</a>" .
                        "</a>"
                        : '-';
                }

                return $row->{$relation . '_id'} ? $row->{$relation}->name : '-';
            })
            ->addColumn('actions', function ($row) use ($config) {
                return $this->generateActionButtons($row, $config);
            })
            ->rawColumns(['actions', $config['relation'] . '_id', $config['type'] . '_id'])
            ->make(true);
    }

    public function insertInvoice(array $request)
    {
        DB::beginTransaction();

        try {
            $type = $request['type'];
            $mapping = self::TYPE_CONFIG[$type] ?? null;

            if (!$mapping) {
                throw new \InvalidArgumentException('Invalid invoice type');
            }

            $relationId = $mapping['relation'] . '_id';
            $typeId = $mapping['type'] . '_id';

            if ($mapping['type'] === 'plan') {
                $amount = Plan::where('id', $request[$typeId] ?? null)->value('monthly_price');
            } else {
                $fee = Fee::findOrFail($request[$typeId] ?? null);
                if (!$fee) {
                    throw new \InvalidArgumentException(trans('admin/fees.feeNotFound'));
                }

                $student = Student::findOrFail($request[$relationId] ?? null);

                if (!$student) {
                    throw new \InvalidArgumentException(trans('admin/fees.studentNotFound'));
                }

                if ($fee->grade_id !== $student->grade_id) {
                    throw new \InvalidArgumentException(trans('admin/fees.feeGradeMismatch'));
                }

                $amount = $fee->amount;
            }

            $invoiceData = [
                'date' => date('Y-m-d'),
                'amount' => $amount ?? 0.00,
                $typeId => $request[$typeId] ?? null,
                $relationId => $request[$relationId] ?? null,
            ];

            // Remove any null values
            $invoiceData = array_filter($invoiceData, fn($value) => !is_null($value));

            $invoice = Invoice::create($invoiceData);

            $this->createAccountTransaction($mapping, $invoice, $request[$relationId]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/invoices.invoice')]),
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

    public function deleteInvoice($id): array
    {
        DB::beginTransaction();

        try {
            $invoice = Invoice::select('id', 'amount')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($invoice)) {
                return $dependencyCheck;
            }

            $invoice->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/invoices.invoice')]),
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
        $typeId = $config['type'];

        return '<div class="align-items-center">
            <button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                id="delete-button"
                data-id="' . $row->id . '"
                data-amount="' . $row->amount . '"
                data-' . $relationId . '_id="' . $row->{$relationId}->name . '"
                data-' . $typeId . '_id="' . $row->{$typeId}->name . '"
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

        $actions = ['insert', 'delete'];

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

    protected function createAccountTransaction($mapping, $invoice, $relationId)
    {
        $accountModel = $mapping['account_model'];

        $accountModel::create([
            'type' => 1, // 1 - Invoice, 2 - Receipt, 3 - Refund
            $mapping['relation'] . '_id' => $relationId,
            'invoice_id' => $invoice->id,
            'debit' => $invoice->amount,
            'credit' => 0.00,
        ]);
    }

    public function checkDependenciesForSingleDeletion($teacher): array|null
    {
        return $this->checkForSingleDependencies($teacher, $this->relationships, $this->transModelKey);
    }
}
