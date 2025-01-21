<?php

namespace App\Http\Controllers\Admin\Students;

use App\Models\Student;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\ProfilePicService;
use App\Http\Requests\Admin\ProfilePicRequest;

class StudentsDetailsController extends Controller
{
    use ValidatesExistence;

    protected $profilePicService;

    public function __construct(ProfilePicService $profilePicService)
    {
        $this->profilePicService = $profilePicService;
    }

    public function index($id)
    {
        $student = Student::findOrFail($id);

        return view('admin.students.details.index', compact('student'));
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
