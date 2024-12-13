<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StagesRequest;
use App\Models\Stage;
use App\Traits\ValidatesExistence;
use App\Services\Admin\StageService;

class StagesController extends Controller
{
    use ValidatesExistence;

    protected $stageService;

    public function __construct(StageService $stageService)
    {
        $this->stageService = $stageService;
    }

    public function index(Request $request)
    {
        $stagesQuery = Stage::query()->select('id', 'name', 'is_active');

        if ($request->ajax()) {
            return $this->stageService->getStagesForDatatable($stagesQuery);
        }

        return view('admin.stages.index');
    }

    public function insert(StagesRequest $request)
    {
        $result = $this->stageService->insertStage($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(StagesRequest $request)
    {
        $result = $this->stageService->updateStage($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'stages');

        $result = $this->stageService->deleteStage($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'stages');

        $result = $this->stageService->deleteSelectedStage($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
