<?php

namespace App\Services\Admin\Platform;

use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class PlanService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['teachers'];
    protected $transModelKey = 'admin/plans.plans';

    public function getPlansForDatatable($plansQuery)
    {
        return datatables()->eloquent($plansQuery)
            ->addIndexColumn()
->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('monthly_price', function ($row) {
                return formatCurrency($row->monthly_price) . ' ' . trans('main.currency');
            })
            ->editColumn('term_price', function ($row) {
                return formatCurrency($row->term_price) . ' ' . trans('main.currency');
            })
            ->editColumn('year_price', function ($row) {
                return formatCurrency($row->year_price) . ' ' . trans('main.currency');
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge rounded-pill bg-label-success text-capitalized">'.trans('main.active').'</span>' : '<span class="badge rounded-pill bg-label-secondary text-capitalized">'.trans('main.inactive').'</span>';
            })
            ->addColumn('actions', function ($row) {
                return
                    '<div class="align-items-center">' .
                    '<span class="text-nowrap">
                        <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                            tabindex="0" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal"
                            id="edit-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                            data-description_ar="' . $row->getTranslation('description', 'ar') . '" data-description_en="' . $row->getTranslation('description', 'en') . '"
                            data-monthly_price="' . $row->monthly_price . '" data-term_price="' . $row->term_price . '" data-year_price="' . $row->year_price . '"
                            data-student_limit="' . $row->student_limit . '" data-parent_limit="' . $row->parent_limit . '" data-assistant_limit="' . $row->assistant_limit . '"
                            data-group_limit="' . $row->group_limit . '" data-quiz_monthly_limit="' . $row->quiz_monthly_limit . '"
                            data-quiz_term_limit="' . $row->quiz_term_limit . '" data-quiz_year_limit="' . $row->quiz_year_limit . '"
                            data-assignment_monthly_limit="' . $row->assignment_monthly_limit . '" data-assignment_term_limit="' . $row->assignment_term_limit . '"
                            data-assignment_year_limit="' . $row->assignment_year_limit . '" data-attendance_reports="' . ($row->attendance_reports ? '1' : '0') . '"
                            data-financial_reports="' . ($row->financial_reports ? '1' : '0') . '" data-performance_reports="' . ($row->performance_reports ? '1' : '0') . '"
                            data-whatsapp_messages="' . ($row->whatsapp_messages ? '1' : '0') . '" data-is_active="' . ($row->is_active ? '1' : '0') . '">
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
            ->rawColumns(['selectbox', 'is_active', 'actions'])
            ->make(true);
    }

    public function insertPlan(array $request)
    {
        DB::beginTransaction();

        try {
            Plan::create([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'description' => ['ar' => $request['description_ar'], 'en' => $request['description_en']],
                'monthly_price' => $request['monthly_price'],
                'term_price' => $request['term_price'],
                'year_price' => $request['year_price'],
                'student_limit' => $request['student_limit'],
                'parent_limit' => $request['parent_limit'],
                'assistant_limit' => $request['assistant_limit'],
                'group_limit' => $request['group_limit'],
                'quiz_monthly_limit' => $request['quiz_monthly_limit'],
                'quiz_term_limit' => $request['quiz_term_limit'],
                'quiz_year_limit' => $request['quiz_year_limit'],
                'assignment_monthly_limit' => $request['assignment_monthly_limit'],
                'assignment_term_limit' => $request['assignment_term_limit'],
                'assignment_year_limit' => $request['assignment_year_limit'],
                'attendance_reports' => $request['attendance_reports'] ?? false,
                'financial_reports' => $request['financial_reports'] ?? false,
                'performance_reports' => $request['performance_reports'] ?? false,
                'whatsapp_messages' => $request['whatsapp_messages'] ?? false,
            ]);


            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/plans.plan')]),
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

    public function updatePlan($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $plan = Plan::findOrFail($id);

            $plan->update([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'description' => ['ar' => $request['description_ar'], 'en' => $request['description_en']],
                'monthly_price' => $request['monthly_price'],
                'term_price' => $request['term_price'],
                'year_price' => $request['year_price'],
                'student_limit' => $request['student_limit'],
                'parent_limit' => $request['parent_limit'],
                'assistant_limit' => $request['assistant_limit'],
                'group_limit' => $request['group_limit'],
                'quiz_monthly_limit' => $request['quiz_monthly_limit'],
                'quiz_term_limit' => $request['quiz_term_limit'],
                'quiz_year_limit' => $request['quiz_year_limit'],
                'assignment_monthly_limit' => $request['assignment_monthly_limit'],
                'assignment_term_limit' => $request['assignment_term_limit'],
                'assignment_year_limit' => $request['assignment_year_limit'],
                'attendance_reports' => $request['attendance_reports'] ?? false,
                'financial_reports' => $request['financial_reports'] ?? false,
                'performance_reports' => $request['performance_reports'] ?? false,
                'whatsapp_messages' => $request['whatsapp_messages'] ?? false,
                'is_active' => $request['is_active'] ?? false,
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/plans.plan')]),
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

    public function deletePlan($id): array
    {
        DB::beginTransaction();

        try {
            $plan = Plan::select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($plan)) {
                return $dependencyCheck;
            }

            $plan->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/plans.plan')]),
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

    public function deleteSelectedPlans($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $plans = Plan::whereIn('id', $ids)
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($plans)) {
                return $dependencyCheck;
            }

            Plan::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/plans.plans'))]),
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

    public function checkDependenciesForSingleDeletion($plan)
    {
        return $this->checkForSingleDependencies($plan, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($plans)
    {
        return $this->checkForMultipleDependencies($plans, $this->relationships, $this->transModelKey);
    }
}
