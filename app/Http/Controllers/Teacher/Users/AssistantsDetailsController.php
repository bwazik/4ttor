<?php

namespace App\Http\Controllers\Admin\assistants;

use App\Models\Assistant;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\ProfilePicService;
use App\Http\Requests\Admin\ProfilePicRequest;

class AssistantsDetailsController extends Controller
{
    use ValidatesExistence;

    protected $profilePicService;

    public function __construct(ProfilePicService $profilePicService)
    {
        $this->profilePicService = $profilePicService;
    }

    public function index($id)
    {
        $assistant = Assistant::findOrFail($id);

        return view('admin.assistants.details.index', compact('assistant'));
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
