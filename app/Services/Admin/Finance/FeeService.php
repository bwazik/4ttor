<?php

namespace App\Services\Admin\Finance;

use App\Models\Fee;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class FeeService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['invoices'];

    protected $transModelKey = 'admin/fees.fees';

    public function getFeesForDatatable($feesQuery)
    {
        return datatables()->eloquent($feesQuery)
            ->addIndexColumn()
->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('amount', function ($row) {
                return formatCurrency($row->amount) . ' ' . trans('main.currency');
            })
            ->editColumn('teacher_id', function ($row) {
                return "<a target='_blank' href='" . route('admin.teachers.details', $row->teacher_id) . "'>" . ($row->teacher_id ? $row->teacher->name : '-') . "</a>";
            })
            ->editColumn('grade_id', function ($row) {
                return $row->grade_id ? $row->grade->name : '-';
            })
            ->addColumn('actions', function ($row) {
                return
                    '<div class="align-items-center">' .
                    '<span class="text-nowrap">
                        <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                            tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal"
                            id="edit-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                            data-amount="' . $row->amount . '" data-teacher_id="' . $row->teacher_id . '" data-grade_id="' . $row->grade_id . '">
                            <i class="ri-edit-box-line ri-20px"></i>
                        </button>
                    </span>' .
                    '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                            id="delete-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                            data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                        </button>' .
                    '</div>';
            })
            ->rawColumns(['selectbox', 'teacher_id', 'actions'])
            ->make(true);
    }

    public function insertFee(array $request)
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::findOrFail($request['teacher_id']);
            if (!$teacher->grades()->where('grade_id', $request['grade_id'])->exists()) {
                return [
                    'status' => 'error',
                    'message' => trans('main.validateTeacherGrades'),
                ];
            }

            Fee::create([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'amount' => $request['amount'],
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/fees.fee')]),
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

    public function updateFee($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::findOrFail($request['teacher_id']);
            if (!$teacher->grades()->where('grade_id', $request['grade_id'])->exists()) {
                return [
                    'status' => 'error',
                    'message' => trans('main.validateTeacherGrades'),
                ];
            }

            $fee = Fee::findOrFail($id);

            $fee->update([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'amount' => $request['amount'],
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/fees.fee')]),
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

    public function deleteFee($id): array
    {
        DB::beginTransaction();

        try {
            $fee = Fee::select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($fee)) {
                return $dependencyCheck;
            }

            $fee->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/fees.fee')]),
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

    public function deleteSelectedFees($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $fees = Fee::whereIn('id', $ids)
                ->select('id', 'name')
                ->orderBy('id')
                ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($fees)) {
                return $dependencyCheck;
            }

            Fee::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/fees.fees'))]),
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

    public function checkDependenciesForSingleDeletion($fee)
    {
        return $this->checkForSingleDependencies($fee, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($fees)
    {
        return $this->checkForMultipleDependencies($fees, $this->relationships, $this->transModelKey);
    }
}
