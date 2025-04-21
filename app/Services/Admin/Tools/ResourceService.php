<?php

namespace App\Services\Admin\Tools;

use App\Models\Resource;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;
use App\Services\Admin\FileUploadService;

class ResourceService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = [];
    protected $transModelKey = 'admin/resources.resources';
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function insertResource(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            if ($validationResult = $this->validateTeacherGrade($request['grade_id'], $request['teacher_id']))
                return $validationResult;

            Resource::create([
                'title' => ['ar' => $request['title_ar'], 'en' => $request['title_en']],
                'description' => $request['description'] ?? null,
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
                'video_url' => $request['video_url'] ?? null,
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/resources.resource')]));
        });
    }

    public function updateResource($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            if ($validationResult = $this->validateTeacherGrade($request['grade_id'], $request['teacher_id']))
                return $validationResult;

            $resource = Resource::findOrFail($id);
            $resource->update([
                'title' => ['ar' => $request['title_ar'], 'en' => $request['title_en']],
                'description' => $request['description'] ?? null,
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
                'video_url' => $request['video_url'] ?? null,
                'is_active' => $request['is_active'] ?? 0,
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/resources.resource')]));
        });
    }

    public function deleteResource($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            $resource = Resource::select('id', 'title')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($resource)) {
                return $dependencyCheck;
            }

            $this->fileUploadService->deleteRelatedFiles($resource, null, 'file_path', true);

            $resource->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/resources.resource')]));
        });
    }

    public function checkDependenciesForSingleDeletion($resource)
    {
        return $this->checkForSingleDependencies($resource, $this->relationships, $this->transModelKey);
    }
}
