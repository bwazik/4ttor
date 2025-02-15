<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fee;
use App\Models\Grade;
use App\Models\Stage;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\GradeService;
use App\Http\Requests\Admin\GradesRequest;

class GradesController extends Controller
{
    use ValidatesExistence;

    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function index(Request $request)
    {
        $gradesQuery = Grade::query()->select('id', 'name', 'is_active', 'stage_id');

        if ($request->ajax()) {
            return $this->gradeService->getGradesForDatatable($gradesQuery);
        }

        $stages = Stage::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.grades.index', compact('stages'));
    }

    public function insert(GradesRequest $request)
    {
        $result = $this->gradeService->insertGrade($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(GradesRequest $request)
    {
        $result = $this->gradeService->updateGrade($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'grades');

        $result = $this->gradeService->deleteGrade($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'grades');

        $result = $this->gradeService->deleteSelectedGrades($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
