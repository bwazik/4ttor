<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\SubjectService;
use App\Http\Requests\Admin\SubjectsRequest;

class SubjectsController extends Controller
{
    use ValidatesExistence;

    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index(Request $request)
    {
        $subjectsQuery = Subject::query()->select('id', 'name', 'is_active');

        if ($request->ajax()) {
            return $this->subjectService->getSubjectsForDatatable($subjectsQuery);
        }

        return view('admin.subjects.index');
    }

    public function insert(SubjectsRequest $request)
    {
        $result = $this->subjectService->insertSubject($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(SubjectsRequest $request)
    {
        $result = $this->subjectService->updateSubject($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'subjects');

        $result = $this->subjectService->deleteSubject($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'subjects');

        $result = $this->subjectService->deleteSelectedSubject($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
