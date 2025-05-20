<?php

namespace App\Http\Controllers\Admin\Platform;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use App\Services\Admin\Platform\SubjectService;
use App\Http\Requests\Admin\Platform\SubjectsRequest;

class SubjectsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

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

        return view('admin.platform.subjects.index');
    }

    public function insert(SubjectsRequest $request)
    {
        $result = $this->subjectService->insertSubject($request->validated());

        return $this->conrtollerJsonResponse($result, "subjects");
    }

    public function update(SubjectsRequest $request)
    {
        $result = $this->subjectService->updateSubject($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result, "subjects");
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'subjects');

        $result = $this->subjectService->deleteSubject($request->id);

        return $this->conrtollerJsonResponse($result, "subjects");
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'subjects');

        $result = $this->subjectService->deleteSelectedSubjects($request->ids);

        return $this->conrtollerJsonResponse($result, "subjects");
    }
}
