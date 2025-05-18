<?php

namespace App\Http\Controllers\Admin\Platform;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\PublicValidatesTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Admin\Platform\PlanService;
use App\Http\Requests\Admin\Platform\PlansRequest;

class PlansController extends Controller
{
    use ValidatesExistence, PublicValidatesTrait;

    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    public function index(Request $request)
    {
        $plansQuery = Plan::query()->select(['id', 'name', 'description', 'monthly_price', 'term_price', 'year_price', 'student_limit', 'parent_limit', 'assistant_limit', 'group_limit', 'quiz_monthly_limit', 'quiz_term_limit', 'quiz_year_limit', 'assignment_monthly_limit', 'assignment_term_limit', 'assignment_year_limit', 'attendance_reports', 'financial_reports', 'performance_reports', 'whatsapp_messages', 'is_active']);

        if ($request->ajax()) {
            return $this->planService->getPlansForDatatable($plansQuery);
        }

        return view('admin.platform.plans.index');
    }

    public function insert(PlansRequest $request)
    {
        $result = $this->planService->insertPlan($request->validated());

        return $this->conrtollerJsonResponse($result, "plans:index");
    }

    public function update(PlansRequest $request)
    {
        $result = $this->planService->updatePlan($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result, "plans:index");
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'plans');

        $result = $this->planService->deletePlan($request->id);

        return $this->conrtollerJsonResponse($result, "plans:index");
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'plans');

        $result = $this->planService->deleteSelectedPlans($request->ids);

        return $this->conrtollerJsonResponse($result, "plans:index");
    }
}
