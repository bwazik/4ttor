<?php

namespace App\Http\Controllers\Admin\Users\Parents;

use App\Models\Student;
use App\Models\MyParent;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Users\ParentService;
use App\Http\Requests\Admin\Users\ParentsRequest;

class ParentsController extends Controller
{
    use ValidatesExistence;

    protected $parentService;

    public function __construct(ParentService $parentService)
    {
        $this->parentService = $parentService;
    }

    public function index(Request $request)
    {
        $parentsQuery = MyParent::query()->select('id', 'username', 'name', 'phone', 'email', 'gender', 'is_active');

        if ($request->ajax()) {
            return $this->parentService->getParentsForDatatable($parentsQuery);
        }

        $pageStatistics = [
            'totalParents' => MyParent::count(),
            'activeParents' => MyParent::active()->count(),
            'inactiveParents' => MyParent::inactive()->count(),
            'archivedParents' => MyParent::onlyTrashed()->count(),
        ];

        $students = Student::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.users.parents.manage.index', compact('pageStatistics', 'students'));
    }

    public function archived(Request $request)
    {
        $parentsQuery = MyParent::query()->onlyTrashed()->select('id', 'username', 'name', 'phone');

        if ($request->ajax()) {
            return $this->parentService->getArchivedParentsForDatatable($parentsQuery);
        }

        return view('admin.users.parents.archive.index');
    }

    public function insert(ParentsRequest $request)
    {
        $result = $this->parentService->insertParent($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(ParentsRequest $request)
    {
        $result = $this->parentService->updateParent($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->deleteParent($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function archive(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->archiveParent($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restore(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->restoreParent($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->deleteSelectedParents($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function archiveSelected(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->archiveSelectedParents($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function restoreSelected(Request $request)
    {
        $this->validateExistence($request, 'parents');

        $result = $this->parentService->restoreSelectedParents($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }
}
