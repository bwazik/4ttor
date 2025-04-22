<?php

namespace App\Services\Admin;

use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Models\AssignmentFile;
use App\Models\Resource;
use App\Models\SubmissionFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\PublicValidatesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\DatabaseTransactionTrait;

class FileUploadService
{
    use PublicValidatesTrait, DatabaseTransactionTrait;

    public function updateProfilePic($request, $model, $id, $directory = 'students')
    {
        return $this->executeTransaction(function () use ($request, $model, $id, $directory)
        {
            $entity = $model::select('id', 'profile_pic')->findOrFail($id);

            if ($request->hasFile('profile')) {
                $file = $request->file('profile');

                $fileName = uniqid($directory . '_', true) . '.' . $file->getClientOriginalExtension();

                $file->storeAs($directory, $fileName, 'profiles');

                $oldPicture = $entity->profile_pic;
                if ($oldPicture && Storage::disk('profiles')->exists($directory . '/' . $oldPicture)) {
                    Storage::disk('profiles')->delete($directory . '/' . $oldPicture);
                }

                $entity->profile_pic = $fileName;
                $entity->save();

                DB::commit();

                return $this->successResponse(trans('toasts.profilePicUpdated'));
            }

            return $this->errorResponse(trans('toasts.noFileUploaded'));
        });
    }

    public function uploadFile(Request $request, string $entityType, int $entityId, string $fileType = 'attachment')
    {
        return $this->executeTransaction(function () use ($request, $entityType, $entityId, $fileType) {
            $file = $request->file('file');

            if (!$file) {
                return $this->errorResponse(trans('toasts.noFileUploaded'));
            }

            if (!$file->isValid()) {
                return $this->errorResponse(trans('toasts.invalidUploadedFile'));
            }

            $timestamp = now()->format('YmdHis');
            $fileName = str_replace(['/', '-', '–', ' ', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $file->getClientOriginalName()); // Sanitize file name
            $path = '';
            $guard = getActiveGuard();
            $user = Auth::guard($guard)->user();
            $fileModelClass = null;
            $fileModelData = [];

            if ($entityType === 'assignment') {
                $assignment = Assignment::findOrFail($entityId);
                $groupIds = $assignment->groups->pluck('id')->toArray();

                if ($guard === 'teacher') {
                    if ($OwnershipValidationResult = $this->checkOwnership($user, $assignment)){
                        return $OwnershipValidationResult;
                    }

                    if ($validationResult = $this->validateTeacherGradeAndGroups($user->id, $groupIds, $assignment->grade_id, true)){
                        return $validationResult;
                    }

                    $existingFiles = AssignmentFile::where('assignment_id', $entityId)->get();
                    $fileCount = $existingFiles->count();
                    $totalSize = $existingFiles->sum('file_size');
                    $newFileSize = $file->getSize();

                    $maxFiles = 5;
                    $maxSize = 30 * 1024 * 1024;

                    if ($fileCount >= $maxFiles) {
                        return $this->errorResponse(trans('toasts.maxFilesLimitReached', ['max' => $maxFiles]));
                    }

                    if (($totalSize + $newFileSize) > $maxSize) {
                        $remainingSizeMB = round(($maxSize - $totalSize) / (1024 * 1024), 2);
                        return $this->errorResponse(trans('toasts.totalSizeExceeded', ['remaining' => $remainingSizeMB]));
                    }
                }

                $customFileName = "{$assignment->id}_{$fileName}_{$timestamp}";
                $path = "assignments/{$assignment->uuid}/teacher/{$customFileName}";
                $fileModelClass = AssignmentFile::class;
                $fileModelData = [
                    'assignment_id' => $entityId,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ];

                $existingFile = $fileModelClass::where('assignment_id', $entityId)
                    ->where('file_name', $file->getClientOriginalName())
                    ->first();

                if ($existingFile) {
                    return $this->errorResponse(trans('toasts.fileAlreadyExists', ['filename' => $fileName]));
                }
            } elseif ($entityType === 'submission') {
                $student = Auth::user();
                if (!$student || !isset($student->uuid)) {
                    return $this->errorResponse(trans('toasts.studentAuthenticationFailed'));
                }
                $assignment = Assignment::findOrFail($entityId);
                $customFileName = "{$fileName}_{$timestamp}";
                $path = "assignments/{$assignment->uuid}/students/{$student->uuid}/{$customFileName}";
                $fileModelClass = SubmissionFile::class;
                $fileModelData = [
                    'submission_id' => $entityId,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ];

                // Check for duplicate file name
                $existingFile = $fileModelClass::where('submission_id', $entityId)
                    ->where('file_name', $file->getClientOriginalName())
                    ->first();
                if ($existingFile) {
                    return $this->errorResponse(trans('toasts.fileAlreadyExists', ['filename' => $fileName]));
                }
            } elseif ($entityType === 'resource') {
                $resource = Resource::findOrFail($entityId);

                if ($guard === 'teacher') {
                    if ($OwnershipValidationResult = $this->checkOwnership($user, $resource)){
                        return $OwnershipValidationResult;
                    }

                    if ($validationResult = $this->validateTeacherGrade($resource->grade_id, $user->id)){
                        return $validationResult;
                    }
                }

                $existingResource = Resource::where('id', $entityId)
                    ->whereNotNull('file_path')
                    ->whereNotNull('file_name')
                    ->first();

                if ($existingResource) {
                    return $this->errorResponse(trans('toasts.maxFilesLimitReached', ['max' => 1]));
                }

                $customFileName = "{$resource->id}_{$fileName}_{$timestamp}";
                $path = "resources/{$resource->uuid}/{$customFileName}";
                $fileModelClass = Resource::class;
                $fileModelData = [
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ];

                $existingFile = $fileModelClass::where('id', $entityId)
                    ->where('file_name', $file->getClientOriginalName())
                    ->first();

                if ($existingFile) {
                    return $this->errorResponse(trans('toasts.fileAlreadyExists', ['filename' => $fileName]));
                }
            } else {
                return $this->errorResponse(trans('main.errorMessage'));
            }

            $success = Storage::disk('s3')->put($path, file_get_contents($file));

            if (!$success) {
                return $this->errorResponse(trans('main.errorMessage'));
            }

            $s3FileSize = Storage::disk('s3')->size($path);
            if ($s3FileSize !== $file->getSize()) {
                Storage::disk('s3')->delete($path); // Clean up
                return $this->errorResponse(trans('main.errorMessage'));
            }

            if ($entityType === 'resource') {
                $fileModel = $fileModelClass::findOrFail($entityId);
                $fileModel->update($fileModelData);
            } else {
                $fileModel = $fileModelClass::create($fileModelData);
            }

            return $this->successResponse(trans('toasts.fileUploadedSuccessfully'));
        });
    }

    public function downloadFile(string $entityType, int $fileId)
    {
        try {
            $fileModelClass = match ($entityType) {
                'assignment' => AssignmentFile::class,
                'submission' => SubmissionFile::class,
                'resource' => Resource::class,
                default => $this->errorResponse(trans('main.errorMessage')),
            };

            $file = $fileModelClass::findOrFail($fileId);

            $guard = getActiveGuard();
            $user = Auth::guard($guard)->user();

            if ($entityType === 'assignment') {
                $assignment = Assignment::findOrFail($file->assignment_id);
                $groupIds = $assignment->groups->pluck('id')->toArray();
            }

            if ($guard === 'teacher') {
                if ($entityType === 'assignment') {
                    if ($OwnershipValidationResult = $this->checkOwnership($user, $file->assignment)){
                        return $OwnershipValidationResult;
                    }
                    if ($validationResult = $this->validateTeacherGradeAndGroups($user->id, $groupIds, $assignment->grade_id, true)){
                        return $validationResult;
                    }
                } elseif ($entityType === 'resource') {
                    if ($OwnershipValidationResult = $this->checkOwnership($user, $file)){
                        return $OwnershipValidationResult;
                    }
                }
            }

            $filePath = $file->file_path;

            if (!$filePath) {
                return;
            }

            if (!Storage::disk('s3')->exists($filePath)) {
                $this->errorResponse(trans('toasts.fileNotFound'));
            }

            return Storage::disk('s3')->download($filePath, $file->file_name);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return $this->productionErrorResponse($e);
        }
    }

    public function deleteFile(string $entityType, int $fileId)
    {
        return $this->executeTransaction(function () use ($entityType, $fileId)
        {
            $fileModelClass = match ($entityType) {
                'assignment' => AssignmentFile::class,
                'submission' => SubmissionFile::class,
                'resource' => Resource::class,
                default => $this->errorResponse(trans('main.errorMessage')),
            };

            $file = $fileModelClass::findOrFail($fileId);

            $guard = getActiveGuard();
            $user = Auth::guard($guard)->user();

            if ($entityType === 'assignment') {
                $assignment = Assignment::findOrFail($file->assignment_id);
                $groupIds = $assignment->groups->pluck('id')->toArray();
            }

            if (!$user) {
                return $this->errorResponse("Unauthenticated");
            }

            if ($guard === 'teacher') {
                if ($entityType === 'assignment') {
                    if ($OwnershipValidationResult = $this->checkOwnership($user, $file->assignment)){
                        return $OwnershipValidationResult;
                    }
                    
                    if ($validationResult = $this->validateTeacherGradeAndGroups($user->id, $groupIds, $assignment->grade_id, true)){
                        return $validationResult;
                    }
                } elseif ($entityType === 'resource') {
                    if ($OwnershipValidationResult = $this->checkOwnership($user, $file)){
                        return $OwnershipValidationResult;
                    }
                }
            }

            $filePath = $file->file_path;
            if (Storage::disk('s3')->exists($filePath)) {
                Storage::disk('s3')->delete($filePath);
            }

            if ($entityType === 'submission' || $entityType === 'assignment') {
                $file->delete();
            }elseif ($entityType === 'resource') {
                $file->update([
                    'file_path' => null,
                    'file_name' => null,
                    'file_size' => 0,
                ]);
            }

            return $this->successResponse(trans('toasts.fileDeletedSuccessfully'));
        });
    }

    public function deleteRelatedFiles($models, ?string $relation = null, string $pathColumn = 'file_path', bool $deleteModelFile = false)
    {
        $models = $models instanceof Collection ? $models : collect([$models]);

        $models->each(function ($model) use ($relation, $pathColumn, $deleteModelFile) {
            if ($deleteModelFile && !empty($model->$pathColumn)) {
                if (Storage::disk('s3')->exists($model->$pathColumn)) {
                    try {
                        Storage::disk('s3')->delete($model->$pathColumn);
                    } catch (\Exception $e) {
                        Log::error("Failed to delete file from S3: {$model->$pathColumn}", ['exception' => $e->getMessage()]);
                    }
                }
            }

            if ($relation) {
                $model->$relation()->each(function ($related) use ($pathColumn) {
                    if (!empty($related->$pathColumn) && Storage::disk('s3')->exists($related->$pathColumn)) {
                        try {
                            Storage::disk('s3')->delete($related->$pathColumn);
                        } catch (\Exception $e) {
                            Log::error("Failed to delete file from S3: {$related->$pathColumn}", ['exception' => $e->getMessage()]);
                        }
                    }
                    $related->delete();
                });
            }
        });
    }
}
