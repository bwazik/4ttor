<?php

namespace App\Http\Controllers\Teacher\Users;

use App\Models\Assistant;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Users\AssistantService;
use App\Http\Requests\Admin\AssistantsRequest;

class AssistantsController extends Controller
{
    use ValidatesExistence;

    protected $assistantService;

    public function __construct(AssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
    }

    public function index(Request $request)
    {
        $assistantsQuery = Assistant::query()->select('id', 'username', 'name', 'phone', 'email', 'is_active', 'profile_pic');

        if ($request->ajax()) {
            return $this->assistantService->getAssistantsForDatatable($assistantsQuery);
        }

        return view('teacher.users.assistants.manage.index');
    }

    public function archived(Request $request)
    {
        $assistantsQuery = Assistant::query()->onlyTrashed()->select('id', 'username', 'name', 'phone', 'profile_pic');

        if ($request->ajax()) {
            return $this->assistantService->getArchivedAssistantsForDatatable($assistantsQuery);
        }

        return view('teacher.users.assistants.archive.index');
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

    public function archive(Request $request)
    {
        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->archiveAssistant($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restore(Request $request)
    {
        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->restoreAssistant($request->id);

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

    public function archiveSelected(Request $request)
    {
        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->archiveSelectedAssistants($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restoreSelected(Request $request)
    {
        $this->validateExistence($request, 'assistants');

        $result = $this->assistantService->restoreSelectedAssistants($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
