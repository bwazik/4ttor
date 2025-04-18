<?php

namespace App\Services\Admin;

use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Models\AssignmentFile;
use App\Models\SubmissionFile;
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

            // Validate file integrity
            if (!$file->isValid()) {
                return $this->errorResponse(trans('toasts.invalidUploadedFile'));
            }

            $timestamp = now()->format('YmdHis');
            $fileName = str_replace(['/', '-', '–', ' ', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $file->getClientOriginalName()); // Sanitize file name
            $path = '';
            $fileModelClass = null;
            $fileModelData = [];

            if ($entityType === 'assignment') {
                $guard = getActiveGuard();
                $assignment = Assignment::findOrFail($entityId);

                if ($guard === 'teacher') {
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

                // Check for duplicate file name
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
            // } elseif ($entityType === 'teacher_library') {
            //     $teacher = Auth::user();
            //     if (!$teacher || !isset($teacher->uuid)) {
            //         return $this->errorResponse('Teacher authentication failed or UUID missing.');
            //     }
            //     $customFileName = "{$fileName}_{$timestamp}";
            //     $path = "teacher_library/{$teacher->uuid}/{$fileType}/{$customFileName}";
            //     $fileModelClass = TeacherLibraryFile::class;
            //     $fileModelData = [
            //         'teacher_id' => $entityId,
            //         'file_type' => $fileType,
            //         'file_path' => $path,
            //         'file_name' => $file->getClientOriginalName(),
            //         'size' => $file->getSize(),
            //     ];

            //     // Check for duplicate file name
            //     $existingFile = $fileModelClass::where('teacher_id', $entityId)
            //         ->where('file_type', $fileType)
            //         ->where('file_name', $fileName)
            //         ->first();
            //     if ($existingFile) {
            //         return $this->errorResponse("A file named '{$fileName}' already exists in this library section.");
            //     }
            } else {
                return $this->errorResponse(trans('main.errorMessage'));
            }

            // Upload to S3
            $success = Storage::disk('s3')->put($path, file_get_contents($file));

            if (!$success) {
                return $this->errorResponse(trans('main.errorMessage'));
            }

            // Verify file size on S3
            $s3FileSize = Storage::disk('s3')->size($path);
            if ($s3FileSize !== $file->getSize()) {
                Storage::disk('s3')->delete($path); // Clean up
                return $this->errorResponse(trans('main.errorMessage'));
            }

            // Save to database
            $fileModel = $fileModelClass::create($fileModelData);

            return $this->successResponse(trans('toasts.fileUploadedSuccessfully'));
        });
    }

    public function downloadFile(string $entityType, int $fileId)
    {
        try {
            $fileModelClass = match ($entityType) {
                'assignment' => AssignmentFile::class,
                'submission' => SubmissionFile::class,
                default => $this->errorResponse(trans('main.errorMessage')),
            };

            $file = $fileModelClass::findOrFail($fileId);

            $guard = getActiveGuard();
            $user = Auth::guard($guard)->user();

            if (!$user) {
                return $this->errorResponse("Unauthenticated");
            }

            // if ($guard === 'web') {
            //     if ($entityType === 'assignment') {
            //         $assignment = Assignment::find($file->assignment_id);
            //         if (!$assignment || !$this->isStudentAuthorizedForAssignment($user, $assignment)) {
            //             return response()->json(['error' => 'Unauthorized to download this assignment file.'], 403);
            //         }
            //     } elseif ($entityType === 'submission') {
            //         // Restrict to files uploaded by the student (check uuid in path)
            //         if ($file->submission_id && strpos($file->file_path, $user->uuid) === false) {
            //             return response()->json(['error' => 'Unauthorized to download this submission file.'], 403);
            //         }
            //     } elseif ($entityType === 'teacher_library') {
            //         return response()->json(['error' => 'Students cannot download teacher library files.'], 403);
            //     }
            // } elseif ($guard === 'teacher') {
            //     if ($entityType === 'submission') {
            //         $assignment = Assignment::find($file->submission_id);
            //         if (!$assignment || $assignment->teacher_id != $user->id) {
            //             return response()->json(['error' => 'Unauthorized to download this submission file.'], 403);
            //         }
            //     }
            //     if ($entityType === 'teacher_library' && $file->teacher_id != $user->id) {
            //         return response()->json(['error' => 'Unauthorized to download this library file.'], 403);
            //     }
            // }

            $filePath = $file->file_path;
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
                default => $this->errorResponse(trans('main.errorMessage')),
            };

            $file = $fileModelClass::findOrFail($fileId);

            $guard = getActiveGuard();
            $user = Auth::guard($guard)->user();

            if (!$user) {
                return $this->errorResponse("Unauthenticated");
            }

            // if ($guard === 'web') {
            //     if ($entityType === 'submission') {
            //         if ($file->submission_id && strpos($file->file_path, $user->uuid) === false) {
            //             return response()->json(['error' => 'Unauthorized to delete this submission file.'], 403);
            //         }
            //     } else {
            //         return response()->json(['error' => 'Students can only delete their own submission files.'], 403);
            //     }
            // } elseif ($guard === 'teacher') {
            //     if ($entityType === 'submission') {
            //         $assignment = Assignment::find($file->submission_id);
            //         if (!$assignment || $assignment->teacher_id != $user->id) {
            //             return response()->json(['error' => 'Unauthorized to delete this submission file.'], 403);
            //         }
            //     }
            //     if ($entityType === 'teacher_library' && $file->teacher_id != $user->id) {
            //         return response()->json(['error' => 'Unauthorized to delete this library file.'], 403);
            //     }
            // }

            $filePath = $file->file_path;
            if (Storage::disk('s3')->exists($filePath)) {
                Storage::disk('s3')->delete($filePath);
            }

            $file->delete();

            return $this->successResponse(trans('toasts.fileDeletedSuccessfully'));
        });
    }

    protected function isStudentAuthorizedForAssignment($user, $assignment)
    {
        // Assuming the student is linked to a grade or group
        return $user->grade_id == $assignment->grade_id || $user->groups()->whereIn('group_id', $assignment->groups->pluck('id'))->exists();
    }
}
