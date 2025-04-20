<?php

namespace App\Http\Controllers\Teacher\Users;

use App\Models\Assistant;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\AssistantsRequest;
use App\Services\Teacher\Users\AssistantService;

class AssistantsController extends Controller
{
    use ValidatesExistence;

    protected $assistantService;
    protected $teacherId;

    public function __construct(AssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $assistantsQuery = Assistant::query()
            ->select('id', 'username', 'name', 'phone', 'email', 'is_active', 'profile_pic')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->assistantService->getAssistantsForDatatable($assistantsQuery);
        }

        $baseStatsQuery = Assistant::where('teacher_id', $this->teacherId);

        $pageStatistics = [
            'totalAssistants' => (clone $baseStatsQuery)->count(),
            'activeAssistants' => (clone $baseStatsQuery)->active()->count(),
            'inactiveAssistants' => (clone $baseStatsQuery)->inactive()->count(),
            'archivedAssistants' => (clone $baseStatsQuery)->onlyTrashed()->count(),
        ];

        return view('teacher.users.assistants.index', compact('pageStatistics'));
    }

    public function insert(AssistantsRequest $request)
    {
        $result = $this->assistantService->insertAssistant($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(AssistantsRequest $request)
    {
        $result = $this->assistantService->updateAssistant($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->deleteAssistant($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }


    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->deleteSelectedAssistants($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
