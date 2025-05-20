<?php

namespace App\Http\Controllers\Teacher\Tools;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Services\PlanLimitService;
use App\Traits\ValidatesExistence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Teacher\Tools\GroupService;
use App\Services\Teacher\Tools\LessonService;
use App\Http\Requests\Admin\Tools\GroupsRequest;

class GroupsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $teacherId;
    protected $groupService;
    protected $lessonService;
    protected $planLimitService;

    public function __construct(GroupService $groupService, LessonService $lessonService)
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
        $this->groupService = $groupService;
        $this->lessonService = $lessonService;
        $this->planLimitService = new PlanLimitService($this->teacherId);
    }

    public function index(Request $request)
    {
        $groupsQuery = Group::query()->with(['grade:id,name'])
            ->select('id', 'uuid', 'name', 'grade_id', 'day_1', 'day_2', 'time', 'is_active', 'created_at', 'updated_at')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->groupService->getGroupsForDatatable($groupsQuery);
        }

        $baseStatsQuery = Group::where('teacher_id', $this->teacherId);

        $pageStatistics = Cache::remember("groups:teacher:{$this->teacherId}:stats", 3600, function () use ($baseStatsQuery) {
            return [
                'totalGroups' => (clone $baseStatsQuery)->count(),
                'activeGroups' => (clone $baseStatsQuery)->active()->count(),
                'inactiveGroups' => (clone $baseStatsQuery)->inactive()->count(),
                'topGrade' => (clone $baseStatsQuery)->select('grade_id', DB::raw('COUNT(*) as group_count'))
                    ->groupBy('grade_id')
                    ->orderByDesc('group_count')
                    ->with('grade:id,name')
                    ->first(),
            ];
        });

        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        return view('teacher.tools.groups.index', compact('grades', 'pageStatistics'));
    }

    public function insert(GroupsRequest $request)
    {
        if (!$this->planLimitService->canPerformAction('groups')) {
            return response()->json(['error' => trans('toasts.limitReached')], 422);
        }

        $result = $this->groupService->insertGroup($request->validated());

        return $this->conrtollerJsonResponse($result, "groups:teacher:{$this->teacherId}:stats");
    }

    public function update(GroupsRequest $request)
    {
        $id = Group::uuid($request->id)->value('id');

        $result = $this->groupService->updateGroup($id, $request->validated());

        return $this->conrtollerJsonResponse($result, "groups:teacher:{$this->teacherId}:stats");
    }

    public function delete(Request $request)
    {
        $id = Group::uuid($request->id)->value('id');
        $request->merge(['id' => $id]);

        $this->validateExistence($request, 'groups');

        $result = $this->groupService->deleteGroup($request->id);

        return $this->conrtollerJsonResponse($result, "groups:teacher:{$this->teacherId}:stats");
    }

    public function deleteSelected(Request $request)
    {
        $ids = Group::whereIn('uuid', $request->ids ?? [])->pluck('id')->toArray();
        !empty($ids) ? $request->merge(['ids' => $ids]) : null;

        $this->validateExistence($request, 'groups');

        $result = $this->groupService->deleteSelectedGroups($request->ids);

        return $this->conrtollerJsonResponse($result, "groups:teacher:{$this->teacherId}:stats");
    }

    public function lessons(Request $request, $uuid)
    {
        $group = Group::select('id', 'uuid', 'name', 'teacher_id')
            ->uuid($uuid)
            ->where('teacher_id', $this->teacherId)
            ->firstOrFail();

        $lessonsQuery = Lesson::query()->with(['group'])
            ->select('id', 'uuid', 'title', 'group_id', 'date', 'time', 'status')
            ->where('group_id', $group->id);

        if ($request->ajax()) {
            return $this->lessonService->getLessonsForDatatable($lessonsQuery);
        }

        return view('teacher.tools.groups.lessons', compact('group'));
    }
}
