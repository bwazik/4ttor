<?php

namespace App\Http\Controllers\Teacher\Users;

use App\Models\Assistant;
use Illuminate\Http\Request;
use App\Services\PlanLimitService;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Teacher\Users\AssistantService;
use App\Http\Requests\Admin\Users\AssistantsRequest;

class AssistantsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $teacherId;
    protected $assistantService;
    protected $planLimitService;

    public function __construct(AssistantService $assistantService)
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
        $this->assistantService = $assistantService;
        $this->planLimitService = new PlanLimitService($this->teacherId);
    }

    public function index(Request $request)
    {
        $assistantsQuery = Assistant::query()
            ->select('id', 'uuid', 'username', 'name', 'phone', 'email', 'is_active', 'profile_pic')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->assistantService->getAssistantsForDatatable($assistantsQuery);
        }

        $baseStatsQuery = Assistant::where('teacher_id', $this->teacherId);

        $pageStatistics = Cache::remember("assistants:teacher:{$this->teacherId}:stats", 3600, function () use ($baseStatsQuery) {
            return [
                'totalAssistants' => (clone $baseStatsQuery)->count(),
                'activeAssistants' => (clone $baseStatsQuery)->active()->count(),
                'inactiveAssistants' => (clone $baseStatsQuery)->inactive()->count(),
                'archivedAssistants' => (clone $baseStatsQuery)->onlyTrashed()->count(),
            ];
        });

        return view('teacher.users.assistants.index', compact('pageStatistics'));
    }

    public function insert(AssistantsRequest $request)
    {
        if (!$this->planLimitService->canPerformAction('assistants')) {
            return response()->json(['error' => trans('toasts.limitReached')], 422);
        }

        $result = $this->assistantService->insertAssistant($request->validated());

        return $this->conrtollerJsonResponse($result, "assistants:teacher:{$this->teacherId}:stats");
    }

    public function update(AssistantsRequest $request)
    {
        $id = Assistant::uuid($request->id)->value('id');

        $result = $this->assistantService->updateAssistant($id, $request->validated());

        return $this->conrtollerJsonResponse($result, "assistants:teacher:{$this->teacherId}:stats");
    }

    public function delete(Request $request)
    {
        $id = Assistant::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->deleteAssistant($request->id);

        return $this->conrtollerJsonResponse($result, "assistants:teacher:{$this->teacherId}:stats");
    }


    public function deleteSelected(Request $request)
    {
        $ids = Assistant::whereIn('uuid', $request->ids ?? [])->pluck('id')->toArray();
        !empty($ids) ? $request->merge(['ids' => $ids]) : null;

        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->deleteSelectedAssistants($request->ids);

        return $this->conrtollerJsonResponse($result, "assistants:teacher:{$this->teacherId}:stats");
    }
}
