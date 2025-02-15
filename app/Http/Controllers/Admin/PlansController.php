<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\PlanService;
use App\Http\Requests\Admin\PlansRequest;

class PlansController extends Controller
{
    use ValidatesExistence;

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

        return view('admin.plans.index');
    }

    public function insert(PlansRequest $request)
    {
        $result = $this->planService->insertPlan($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(PlansRequest $request)
    {
        $result = $this->planService->updatePlan($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'plans');

        $result = $this->planService->deletePlan($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'plans');

        $result = $this->planService->deleteSelectedPlans($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function getPlanPrice(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        try {
            $plan = Plan::select('monthly_price')->find($validated['plan_id']);

            return response()->json(['status' => 'success', 'data' => $plan->monthly_price]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ]);
        }
    }
}
