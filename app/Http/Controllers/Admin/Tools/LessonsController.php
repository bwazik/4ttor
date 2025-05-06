<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Models\Group;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\LessonService;
use App\Http\Requests\Admin\Tools\LessonsRequest;

class LessonsController extends Controller
{
    use ValidatesExistence;

    protected $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    public function index(Request $request)
    {
        $lessonsQuery = Lesson::query()->with(['group'])
            ->select('id', 'title', 'group_id', 'date', 'time', 'status');

        if ($request->ajax()) {
            return $this->lessonService->getLessonsForDatatable($lessonsQuery);
        }

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $groups = Group::query()->select('id', 'name', 'teacher_id', 'grade_id')
            ->with(['teacher:id,name', 'grade:id,name'])
            ->orderBy('teacher_id')
            ->orderBy('grade_id')
            ->get()
            ->mapWithKeys(function ($group) {
                $gradeName = $group->grade->name ?? 'N/A';
                $teacherName = $group->teacher->name ?? 'N/A';
                return [$group->id => $group->name . ' - ' . $gradeName . ' - ' . $teacherName];
            });

        return view('admin.tools.lessons.index', compact('teachers', 'groups'));
    }

    public function insert(LessonsRequest $request)
    {
        $result = $this->lessonService->insertLesson($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(LessonsRequest $request)
    {
        $result = $this->lessonService->updateLesson($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'lessons');

        $result = $this->lessonService->deleteLesson($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'lessons');

        $result = $this->lessonService->deleteSelectedLessons($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
