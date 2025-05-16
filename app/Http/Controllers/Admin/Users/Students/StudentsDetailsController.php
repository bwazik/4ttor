<?php

namespace App\Http\Controllers\Admin\Users\Students;

use App\Models\Student;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\FileUploadService;
use App\Http\Requests\ProfilePicRequest;

class StudentsDetailsController extends Controller
{
    use ValidatesExistence;

    protected $profilePicService;

    public function __construct(FileUploadService $profilePicService)
    {
        $this->profilePicService = $profilePicService;
    }

    public function index($id)
    {
        $student = Student::findOrFail($id);

        return view('admin.users.students.details.index', compact('student'));
    }

    public function updateProfilePic(ProfilePicRequest $request, $id)
    {
        $result = $this->profilePicService->updateProfilePic($request, Student::class, $id, 'students');

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

}
