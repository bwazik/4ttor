<?php

namespace App\Http\Controllers\Admin\Users\Parents;

use App\Models\MyParent;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Users\ParentService;
use App\Http\Requests\Admin\ParentsRequest;

class ParentsDetailsController extends Controller
{
    use ValidatesExistence;

    protected $parentService;

    public function __construct(ParentService $parentService)
    {
        $this->parentService = $parentService;
    }

    public function index($id)
    {
        $parent = MyParent::findOrFail($id);

        return view('admin.users.parents.details.index', compact('parent'));
    }

    // public function changeProfilePic(Request $request, $id)
    // {

    // }
}
