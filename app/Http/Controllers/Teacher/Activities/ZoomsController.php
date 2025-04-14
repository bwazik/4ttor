<?php

namespace App\Http\Controllers\Teacher\Activities;

use App\Models\Zoom;
use App\Models\Grade;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Teacher\Activities\ZoomService;
use App\Http\Requests\Admin\Activities\ZoomsRequest;

class ZoomsController extends Controller
{
    use ValidatesExistence;

    protected $zoomService;
    protected $teacherId;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index(Request $request)
    {
        $zoomsQuery = Zoom::query()
            ->select('id', 'grade_id', 'group_id', 'meeting_id', 'topic', 'duration', 'start_time', 'start_url', 'join_url', 'created_at', 'updated_at')
            ->where('teacher_id', $this->teacherId);

        if ($request->ajax()) {
            return $this->zoomService->getZoomsForDatatable($zoomsQuery);
        }

        $grades = Grade::whereHas('teachers', fn($query) => $query->where('teacher_id', $this->teacherId))
            ->select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();

        $groups = Group::query()
            ->select('id', 'name', 'grade_id')
            ->where('teacher_id', $this->teacherId)
            ->with('grade:id,name')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(fn($group) => [$group->id => $group->name . ' - ' . $group->grade->name]);


        return view('teacher.activities.zooms.index', compact('grades', 'groups'));
    }

    public function insert(ZoomsRequest $request)
    {
        $result = $this->zoomService->insertZoom($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(ZoomsRequest $request)
    {
        $result = $this->zoomService->updateZoom($request->id, $request->meeting_id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'zooms');

        $result = $this->zoomService->deleteZoom($request->id, $request->meeting_id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'zooms');

        $result = $this->zoomService->deleteSelectedZooms($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
