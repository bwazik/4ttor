<?php

namespace App\Services\Admin\Finance;

use App\Models\Plan;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\TeacherSubscription;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class TeacherSubscriptionService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = [];

    protected $transModelKey = 'admin/teacherSubscriptions.teacherSubscriptions';

    public function getTeacherSubscriptionsForDatatable($teacherSubscriptionsQuery)
    {
        return datatables()->eloquent($teacherSubscriptionsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->editColumn('teacher_id', fn($row) => formatRelation($row->teacher_id, $row->teacher, 'name', 'admin.teachers.details'))
            ->editColumn('plan_id', fn($row) => $row->plan_id ? $row->plan->name : '-')
            ->editColumn('start_date', fn($row) => formatDate($row->start_date))
            ->editColumn('end_date', fn($row) => formatDate($row->end_date))
            ->addColumn('amount', fn($row) => $row->amount . ' ' . trans('main.currency'))
            ->editColumn('status', fn($row) => formatSubscriptionStatus($row->status))
            ->addColumn('actions', fn($row) => $this->generateActionButtons($row))
            ->filterColumn('teacher_id', fn($query, $keyword) => filterByRelation($query, 'teacher', 'name', $keyword))
            ->filterColumn('plan_id', fn($query, $keyword) => filterByRelation($query, 'plan', 'name', $keyword))
            ->rawColumns(['selectbox', 'teacher_id', 'status', 'actions'])
            ->make(true);
    }

    private function generateActionButtons($row): string
    {
        return
            '<div class="align-items-center">' .
                '<span class="text-nowrap">' .
                    '<button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light" ' .
                        'tabindex="0" type="button" ' .
                        'data-bs-toggle="offcanvas" data-bs-target="#edit-modal" ' .
                        'id="edit-button" ' .
                        'data-id="' . $row->id . '" ' .
                        'data-teacher_id="' . $row->teacher_id . '" ' .
                        'data-plan_id="' . $row->plan_id . '" ' .
                        'data-period="' . $row->period . '" ' .
                        'data-amount="' . $row->amount . '" ' .
                        'data-start_date="' . $row->start_date . '" ' .
                        'data-end_date="' . $row->end_date . '" ' .
                        'data-status="' . $row->status . '" ' . '">' .
                        '<i class="ri-edit-box-line ri-20px"></i>' .
                    '</button>' .
                '</span>' .
                '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1" ' .
                    'id="delete-button" ' .
                    'data-id="' . $row->id . '" ' .
                    'data-plan="' . $row->plan->name . '" ' .
                    'data-teacher="' . $row->teacher->name . '" ' .
                    'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                    '<i class="ri-delete-bin-7-line ri-20px text-danger"></i>' .
                '</button>' .
            '</div>';
    }

    public function insertTeacherSubscription(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            if ($validationResult = $this->validateTeacherSubscription($request['teacher_id'], $request['plan_id']))
                return $validationResult;

            $subscription = TeacherSubscription::create([
                'teacher_id' => $request['teacher_id'],
                'plan_id' => $request['plan_id'],
                'period' => $request['period'],
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
                'status' => 1,
            ]);

            if ($validationResult = $this->validateTeacherSubscriptionForInvoice($subscription->id, $request['teacher_id']))
                return $validationResult;

            $invoice = Invoice::create([
                'type' => 1,
                'teacher_id' => $request['teacher_id'],
                'subscription_id' => $subscription->id,
                'amount' => $subscription->amount,
                'date' => now()->format('Y-m-d'),
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'status' => 1,
            ]);

            Transaction::create([
                'type' => 1,
                'teacher_id' => $request['teacher_id'],
                'invoice_id' => $invoice->id,
                'amount' => $subscription->amount,
                'balance_after' => $this->getFounderWalletBalance(),
                'description' => $request['description'] ?? null,
                'date' => now()->format('Y-m-d'),
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]));
        });
    }

    public function updateTeacherSubscription($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            if ($validationResult = $this->validateTeacherSubscription($request['teacher_id'], $request['plan_id'], $id))
                return $validationResult;

            $teacherSubscription = TeacherSubscription::findOrFail($id);
            $teacherSubscription->update([
                'teacher_id' => $request['teacher_id'],
                'plan_id' => $request['plan_id'],
                'period' => $request['period'],
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
                'status' => $request['status'],
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]));
        });
    }

    public function deleteTeacherSubscription($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            $teacherSubscription = TeacherSubscription::select('id')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($teacherSubscription))
                return $dependencyCheck;

            $teacherSubscription->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]));
        });
    }

    public function deleteSelectedTeacherSubscriptions($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            $teacherSubscriptions = TeacherSubscription::whereIn('id', $ids)->select('id')->orderBy('id')->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($teacherSubscriptions)) {
                return $dependencyCheck;
            }

            TeacherSubscription::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]));
        });
    }

    public function checkDependenciesForSingleDeletion($teacherSubscription)
    {
        return $this->checkForSingleDependencies($teacherSubscription, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($teacherSubscriptions)
    {
        return $this->checkForMultipleDependencies($teacherSubscriptions, $this->relationships, $this->transModelKey);
    }
}
