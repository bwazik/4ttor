<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Resource;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\FileUploadService;
use App\Services\Admin\Tools\ResourceService;
use App\Http\Requests\Admin\Tools\ResourcesRequest;

class ResourcesController extends Controller
{
    use ValidatesExistence;

    protected $resourceService;
    protected $fileUploadService;

    public function __construct(ResourceService $resourceService, FileUploadService $fileUploadService)
    {
        $this->resourceService = $resourceService;
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request)
    {
        $query = Resource::with(['teacher', 'grade'])
            ->select('id', 'uuid', 'teacher_id', 'grade_id', 'title', 'description', 'file_path', 'file_name', 'file_size', 'video_url', 'views', 'downloads', 'is_active', 'created_at');

        $query->when($request->grade_id, fn($q) => $q->where('grade_id', $request->grade_id))
            ->when($request->teacher_id, fn($q) => $q->where('teacher_id', $request->teacher_id))
            ->when($request->hide_inactive, fn($q) => $q->where('is_active', true))
            ->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
            }));

        if ($request->sort) {
            [$column, $direction] = explode('-', $request->sort);
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $resources = $query->paginate(6);

        if ($request->expectsJson()) {
            return response()->json([
                'resources' => [
                    'data' => $resources->map(function ($resource) {
                        return [
                            'id' => $resource->id,
                            'title' => $resource->title,
                            'title_ar' => $resource->getTranslation('title', 'ar'),
                            'title_en' => $resource->getTranslation('title', 'en'),
                            'description' => $resource->description,
                            'file_name' => $resource->file_name,
                            'file_size' => $resource->file_size,
                            'video_url' => $resource->video_url,
                            'views' => $resource->views,
                            'downloads' => $resource->downloads,
                            'is_active' => $resource->is_active,
                            'created_at' => $resource->created_at ? isoFormat($resource->created_at) : isoFormat(now()),
                            'grade' => [
                                'name' => $resource->grade->name,
                                'total_students' => $resource->grade->students->count(),
                            ],
                            'teacher' => [
                                'name' => $resource->teacher->name,
                                'profile_pic' => $resource->teacher->profile_pic,
                            ],
                            'teacher_id' => $resource->teacher_id,
                            'grade_id' => $resource->grade_id,
                        ];
                    }),
                    'total' => $resources->total(),
                ],
                'pagination' => $resources->appends(request()->query())->links('partials.paginations')->render(),
            ]);
        }

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $grades = Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.tools.resources.index', compact('resources', 'teachers', 'grades'));
    }

    public function insert(ResourcesRequest $request)
    {
        $result = $this->resourceService->insertResource($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(ResourcesRequest $request)
    {
        $result = $this->resourceService->updateResource($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'teacher_resources');

        $result = $this->resourceService->deleteResource($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function details($id)
    {
        $resource = Resource::with(['teacher', 'grade'])
            ->select('id', 'teacher_id', 'grade_id', 'title', 'description', 'file_path', 'file_name', 'file_size', 'video_url', 'views', 'downloads', 'is_active', 'created_at')
            ->findOrFail($id);

        return view('admin.tools.resources.details', compact('resource'));
    }

    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png'
        ]);

        $result = $this->fileUploadService->uploadFile($request, 'resource', $id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function downloadFile($fileId)
    {
        $result = $this->fileUploadService->downloadFile('resource', $fileId);

        if ($result instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            return $result;
        }

        abort(404);
    }

    public function deleteFile(Request $request)
    {
        $this->validateExistence($request, 'teacher_resources');

        $result = $this->fileUploadService->deleteFile('resource', $request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
