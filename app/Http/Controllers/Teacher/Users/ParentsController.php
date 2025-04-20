<?php

namespace App\Http\Controllers\Teacher\Users;

use App\Models\MyParent;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\ParentsRequest;
use App\Models\Student;
use App\Services\Teacher\Users\ParentService;

class ParentsController extends Controller
{
    use ValidatesExistence;

    protected $parentService;
    protected $teacherId;

    public function __construct(ParentService $parentService)
    {
        $this->parentService = $parentService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $parentsQuery = MyParent::query()
            ->select('id', 'username', 'name', 'phone', 'email', 'gender', 'is_active')
            ->whereHas('students.teachers', fn($query) => $query->where('teachers.id', $this->teacherId));

        if ($request->ajax()) {
            return $this->parentService->getParentsForDatatable($parentsQuery);
        }

        $baseStatsQuery = MyParent::whereHas('students.teachers', fn($q) => $q->where('teachers.id', $this->teacherId));

        $pageStatistics = [
            'totalParents' => (clone $baseStatsQuery)->count(),
            'activeParents' => (clone $baseStatsQuery)->active()->count(),
            'inactiveParents' => (clone $baseStatsQuery)->inactive()->count(),
            'archivedParents' => (clone $baseStatsQuery)->onlyTrashed()->count(),
        ];

        $students = Student::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        return view('teacher.users.parents.index', compact('pageStatistics', 'students'));
    }

    public function insert(ParentsRequest $request)
    {
        $result = $this->parentService->insertParent($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(ParentsRequest $request)
    {
        $result = $this->parentService->updateParent($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->deleteParent($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->deleteSelectedParents($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
