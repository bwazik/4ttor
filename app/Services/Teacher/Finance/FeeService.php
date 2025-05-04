<?php

namespace App\Services\Teacher\Finance;
use App\Models\Fee;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class FeeService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = [];
    protected $transModelKey = 'admin/fees.fees';
    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function getFeesForDatatable($feesQuery)
    {
        return datatables()->eloquent($feesQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->uuid))
            ->editColumn('name', fn($row) => $row->name)
            ->editColumn('amount', fn($row) => formatCurrency($row->amount) . ' ' . trans('main.currency'))
            ->editColumn('grade_id', fn($row) => $row->grade_id ? $row->grade->name : '-')
            ->editColumn('frequency', fn($row) => formatFrequency($row->frequency))
            ->addColumn('actions', fn($row) => $this->generateActionButtons($row))
            ->filterColumn('grade_id', fn($query, $keyword) => filterByRelation($query, 'grade', 'name', $keyword))
            ->rawColumns(['selectbox', 'frequency', 'actions'])
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
                        'data-id="' . $row->uuid . '" ' .
                        'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                        'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                        'data-amount="' . $row->amount . '" ' .
                        'data-grade_id="' . $row->grade_id . '" ' .
                        'data-frequency="' . $row->frequency . '" ' . '">' .
                        '<i class="ri-edit-box-line ri-20px"></i>' .
                    '</button>' .
                '</span>' .
                '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1" ' .
                    'id="delete-button" ' .
                    'data-id="' . $row->uuid . '" ' .
                    'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                    'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                    'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                    '<i class="ri-delete-bin-7-line ri-20px text-danger"></i>' .
                '</button>' .
            '</div>';
    }

    public function insertFee(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            if ($validationResult = $this->validateTeacherGrade($request['grade_id'], $this->teacherId))
                return $validationResult;

            Fee::create([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'amount' => $request['amount'],
                'teacher_id' => $this->teacherId,
                'grade_id' => $request['grade_id'],
                'frequency' => $request['frequency'],
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/fees.fee')]));
        });
    }

    public function updateFee($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            if ($validationResult = $this->validateTeacherGrade($request['grade_id'], $this->teacherId))
                return $validationResult;

            $fee = Fee::findOrFail($id);

            $fee->update([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'amount' => $request['amount'],
                'grade_id' => $request['grade_id'],
                'frequency' => $request['frequency'],
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/fees.fee')]));
        });
    }

    public function deleteFee($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            $fee = Fee::select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($fee))
                return $dependencyCheck;

            $fee->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/fees.fee')]));
        });
    }

    public function deleteSelectedFees($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            $fees = Fee::whereIn('id', $ids)->select('id', 'name')->orderBy('id')->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($fees)) {
                return $dependencyCheck;
            }

            Fee::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/fees.fee')]));
        });
    }

    public function checkDependenciesForSingleDeletion($fee)
    {
        return $this->checkForSingleDependencies($fee, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($fees)
    {
        return $this->checkForMultipleDependencies($fees, $this->relationships, $this->transModelKey);
    }
}
