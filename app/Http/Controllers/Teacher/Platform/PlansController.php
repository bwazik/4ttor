<?php

namespace App\Http\Controllers\Teacher\Platform;

use App\Models\Plan;
use App\Traits\ValidatesExistence;
use App\Models\TeacherSubscription;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Teacher\Tools\GroupService;

class PlansController extends Controller
{
    use ValidatesExistence;

    protected $groupService;
    protected $teacherId;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index()
    {
        $plans = Cache::remember('plans:index', 86400, fn() => Plan::query()
            ->active()
            ->select('id', 'name', 'description', 'monthly_price', 'term_price', 'year_price',
                'student_limit', 'parent_limit', 'assistant_limit', 'group_limit',
                'quiz_monthly_limit', 'quiz_term_limit', 'quiz_year_limit',
                'assignment_monthly_limit', 'assignment_term_limit', 'assignment_year_limit',
                'resource_monthly_limit', 'resource_term_limit', 'resource_year_limit',
                'zoom_monthly_limit', 'zoom_term_limit', 'zoom_year_limit',
                'attendance_reports', 'financial_reports', 'performance_reports',
                'whatsapp_messages', 'instant_customer_service')
            ->orderBy('monthly_price')->get());

        $subscription = TeacherSubscription::where('teacher_id', $this->teacherId)
            ->where('status', 1)
            ->where('end_date', '>=', now())
            ->select('plan_id')->first();

        return view('teacher.platform.plans.index', compact('plans', 'subscription'));
    }
}
