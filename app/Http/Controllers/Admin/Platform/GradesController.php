<?php

namespace App\Http\Controllers\Admin\Platform;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use App\Services\Admin\Platform\GradeService;
use App\Http\Requests\Admin\Platform\GradesRequest;

class GradesController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

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

        return view('admin.platform.grades.index', compact('stages'));
    }

    public function insert(GradesRequest $request)
    {
        $result = $this->gradeService->insertGrade($request->validated());

        return $this->conrtollerJsonResponse($result, "grades");
    }

    public function update(GradesRequest $request)
    {
        $result = $this->gradeService->updateGrade($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result, "grades");
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'grades');

        $result = $this->gradeService->deleteGrade($request->id);

        return $this->conrtollerJsonResponse($result, "grades");
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'grades');

        $result = $this->gradeService->deleteSelectedGrades($request->ids);

        return $this->conrtollerJsonResponse($result, "grades");
    }

    public function getGradeTeachers($id)
    {
        try {
            Grade::select('id')->findOrFail($id);

            $teachers = Teacher::whereHas('grades', function ($query) use ($id) {
                $query->where('grade_id', $id);
            })
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(fn($teacher) => [$teacher->id => $teacher->name]);

            if ($teachers->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('main.noGradesAssigned'),
                ], 404);
            }

            return response()->json(['status' => 'success', 'data' => $teachers]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ], 500);
        }
    }
}
