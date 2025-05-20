<?php

namespace App\Http\Controllers\Teacher\Users;

use App\Models\Student;
use App\Models\MyParent;
use Illuminate\Http\Request;
use App\Services\PlanLimitService;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Teacher\Users\ParentService;
use App\Http\Requests\Admin\Users\ParentsRequest;

class ParentsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $teacherId;
    protected $parentService;
    protected $planLimitService;

    public function __construct(ParentService $parentService)
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
        $this->parentService = $parentService;
        $this->planLimitService = new PlanLimitService($this->teacherId);
    }

    public function index(Request $request)
    {
        $parentsQuery = MyParent::query()
            ->select('id', 'uuid', 'username', 'name', 'phone', 'email', 'gender', 'is_active')
            ->whereHas('students.teachers', fn($query) => $query->where('teachers.id', $this->teacherId));

        if ($request->ajax()) {
            return $this->parentService->getParentsForDatatable($parentsQuery);
        }

        $baseStatsQuery = MyParent::whereHas('students.teachers', fn($q) => $q->where('teachers.id', $this->teacherId));

        $pageStatistics = Cache::remember("parents:teacher:{$this->teacherId}:stats", 3600, function () use ($baseStatsQuery) {
            return [
                'totalParents' => (clone $baseStatsQuery)->count(),
                'activeParents' => (clone $baseStatsQuery)->active()->count(),
                'inactiveParents' => (clone $baseStatsQuery)->inactive()->count(),
                'archivedParents' => (clone $baseStatsQuery)->onlyTrashed()->count(),
            ];
        });

        $students = Student::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'uuid', 'name')
            ->orderBy('id')
            ->pluck('name', 'uuid')
            ->toArray();

        return view('teacher.users.parents.index', compact('pageStatistics', 'students'));
    }

    public function insert(ParentsRequest $request)
    {
        if (!$this->planLimitService->canPerformAction('parents')) {
            return response()->json(['error' => trans('toasts.limitReached')], 422);
        }

        $result = $this->parentService->insertParent($request->validated());

        return $this->conrtollerJsonResponse($result, "parents:teacher:{$this->teacherId}:stats");
    }

    public function update(ParentsRequest $request)
    {
        $id = MyParent::uuid($request->id)->value('id');

        $result = $this->parentService->updateParent($id, $request->validated());

        return $this->conrtollerJsonResponse($result, "parents:teacher:{$this->teacherId}:stats");
    }

    public function delete(Request $request)
    {
        $id = MyParent::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'parents');

        $result = $this->parentService->deleteParent($request->id);

        return $this->conrtollerJsonResponse($result, "parents:teacher:{$this->teacherId}:stats");
    }

    public function deleteSelected(Request $request)
    {
        $ids = MyParent::whereIn('uuid', $request->ids ?? [])->pluck('id')->toArray();
        !empty($ids) ? $request->merge(['ids' => $ids]) : null;

        $this->validateExistence($request, 'parents');

        $result = $this->parentService->deleteSelectedParents($request->ids);

        return $this->conrtollerJsonResponse($result, "parents:teacher:{$this->teacherId}:stats");
    }
}
