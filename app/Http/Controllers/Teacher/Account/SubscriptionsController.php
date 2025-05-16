<?php

namespace App\Http\Controllers\Teacher\Account;

use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Models\TeacherSubscription;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Account\SubscriptionService;


class SubscriptionsController extends Controller
{
    use ValidatesExistence;

    protected $subscriptionService;
    protected $teacherId;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $subscriptionsQuery = TeacherSubscription::query()
            ->with(['plan'])
            ->where('teacher_id', $this->teacherId)
            ->select('id', 'plan_id', 'period', 'start_date', 'end_date', 'status');

        if ($request->ajax()) {
            return $this->subscriptionService->getSubscriptionsForDatatable($subscriptionsQuery);
        }
    }

    public function insert(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'period' => 'required|integer|in:1,2,3',
        ]);

        $result = $this->subscriptionService->insertSubscription($validated);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function cancle(Request $request)
    {
        $this->validateExistence($request, 'teacher_subscriptions');

        $result = $this->subscriptionService->cancleSubscription($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
