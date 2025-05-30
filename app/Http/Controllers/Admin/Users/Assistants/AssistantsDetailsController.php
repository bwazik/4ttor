<?php

namespace App\Http\Controllers\Admin\Users\Assistants;

use App\Models\Assistant;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\FileUploadService;
use App\Http\Requests\ProfilePicRequest;

class AssistantsDetailsController extends Controller
{
    use ValidatesExistence;

    protected $profilePicService;

    public function __construct(FileUploadService $profilePicService)
    {
        $this->profilePicService = $profilePicService;
    }

    public function index($id)
    {
        $assistant = Assistant::findOrFail($id);

        return view('admin.users.assistants.details.index', compact('assistant'));
    }

    public function updateProfilePic(ProfilePicRequest $request, $id)
    {
        $result = $this->profilePicService->updateProfilePic($request, assistant::class, $id, 'assistants');

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
