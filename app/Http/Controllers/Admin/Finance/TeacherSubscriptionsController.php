<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Models\Plan;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Models\TeacherSubscription;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Admin\Finance\TeacherSubscriptionService;
use App\Http\Requests\Admin\Finance\TeacherSubscriptionsRequest;

class TeacherSubscriptionsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $teacherSubscriptionService;

    public function __construct(TeacherSubscriptionService $teacherSubscriptionService)
    {
        $this->teacherSubscriptionService = $teacherSubscriptionService;
    }

    public function index(Request $request)
    {
        $teacherSubscriptionsQuery = TeacherSubscription::query()
            ->with(['teacher', 'plan'])
            ->select('id', 'teacher_id', 'plan_id', 'period', 'start_date', 'end_date', 'status');

        if ($request->ajax()) {
            return $this->teacherSubscriptionService->getTeacherSubscriptionsForDatatable($teacherSubscriptionsQuery);
        }

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $plans = Plan::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.finance.teacherSubscriptions.index', compact('teachers', 'plans'));
    }


    public function insert(TeacherSubscriptionsRequest $request)
    {
        $result = $this->teacherSubscriptionService->insertTeacherSubscription($request->validated());

        return $this->conrtollerJsonResponse($result, "billing:teacher:{$request->teacher_id}:index");
    }

    public function update(TeacherSubscriptionsRequest $request)
    {
        $result = $this->teacherSubscriptionService->updateTeacherSubscription($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result, "billing:teacher:{$request->teacher_id}:index");
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'teacher_subscriptions');

        $teacherId = TeacherSubscription::where('id', $request->id)->value('teacher_id');

        $result = $this->teacherSubscriptionService->deleteTeacherSubscription($request->id);

        return $this->conrtollerJsonResponse($result, "billing:teacher:{$teacherId}:index");
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'teacher_subscriptions');

        $teacherIds = TeacherSubscription::whereIn('id', $request->ids)->pluck('teacher_id')->unique()->toArray();
        $cacheKeys = array_map(fn($id) => "billing:teacher:{$id}:index", $teacherIds);

        $result = $this->teacherSubscriptionService->deleteSelectedTeacherSubscriptions($request->ids);

        return $this->conrtollerJsonResponse($result, $cacheKeys);
    }
}
