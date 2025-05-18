<?php

namespace App\Services\Teacher\Account;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\TeacherSubscription;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class SubscriptionService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function getSubscriptionsForDatatable($subscriptionsQuery)
    {
        return datatables()->eloquent($subscriptionsQuery)
            ->addIndexColumn()
            ->editColumn('plan_id', fn($row) => $row->plan_id ? $row->plan->name : '-')
            ->editColumn('start_date', fn($row) => formatDate($row->start_date))
            ->editColumn('end_date', fn($row) => formatDate($row->end_date))
            ->addColumn('amount', fn($row) => formatCurrency($row->amount) . ' ' . trans('main.currency'))
            ->editColumn('status', fn($row) => formatSubscriptionStatus($row->status))
            ->filterColumn('plan_id', fn($query, $keyword) => filterByRelation($query, 'plan', 'name', $keyword))
            ->rawColumns(['selectbox', 'status'])
            ->make(true);
    }

    public function insertSubscription(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            if ($validationResult = $this->validateTeacherSubscription($this->teacherId, $request['plan_id']))
                return $validationResult;

            if ((int)$request['period'] !== 1) {
                return $this->errorResponse(trans('toasts.periodNotAllowed'));
            }

            $subscription = TeacherSubscription::create([
                'teacher_id' => $this->teacherId,
                'plan_id' => $request['plan_id'],
                'period' => $request['period'],
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addDays(30)->format('Y-m-d'),
                'status' => 1,
            ]);

            if ($validationResult = $this->validateTeacherSubscriptionForInvoice($subscription->id, $this->teacherId))
                return $validationResult;

            $invoice = Invoice::create([
                'type' => 1,
                'teacher_id' => $this->teacherId,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->amount,
                'date' => now()->format('Y-m-d'),
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'status' => 1,
            ]);

            Transaction::create([
                'type' => 1,
                'teacher_id' => $this->teacherId,
                'invoice_id' => $invoice->id,
                'amount' => $subscription->amount,
                'balance_after' => $this->getFounderWalletBalance(),
                'description' => $request['description'] ?? null,
                'date' => now()->format('Y-m-d'),
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/teacherSubscriptions.subscription')]));
        });
    }

    public function cancleSubscription($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            $subscription = TeacherSubscription::findOrFail($id);

            if ($subscription->teacher_id !== $this->teacherId) {
                return $this->errorResponse(trans('toasts.ownershipError'));
            }

            $subscription->update(['status' => 2]);

            return $this->successResponse(trans('main.canceledE', ['item' => trans('admin/teacherSubscriptions.subscription')]));
        });
    }
}
