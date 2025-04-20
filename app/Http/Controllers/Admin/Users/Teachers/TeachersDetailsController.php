<?php

namespace App\Http\Controllers\Admin\Users\Teachers;

use App\Models\Teacher;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\FileUploadService;
use App\Http\Requests\Admin\ProfilePicRequest;

class TeachersDetailsController extends Controller
{
    use ValidatesExistence;

    protected $profilePicService;

    public function __construct(FileUploadService $profilePicService)
    {
        $this->profilePicService = $profilePicService;
    }

    public function index($id)
    {
        $teacher = Teacher::findOrFail($id);

        return view('admin.users.teachers.details.index', compact('teacher'));
    }

    public function updateProfilePic(ProfilePicRequest $request, $id)
    {
        $result = $this->profilePicService->updateProfilePic($request, teacher::class, $id, 'teachers');

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
