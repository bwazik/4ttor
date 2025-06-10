<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Traits\DatabaseTransactionTrait;

class LandingController extends Controller
{
    use ServiceResponseTrait, DatabaseTransactionTrait;

    public function index()
    {
        $metrics = Cache::remember('landing_fun_facts', 1440, function () {
            return [
                'teachers' => Teacher::count(),
                'students' => Student::count(),
                'lessons' => Lesson::count(),
                'groups' => Group::count(),
            ];
        });

        return view('landing.index', compact('metrics'));
    }

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'phone' => 'required|numeric|regex:/^(01)[0-9]{9}$/',
            'message' => 'required|string|max:500'
        ]);

        $result = $this->executeTransaction(function () use ($validated) {
            return $this->successResponse(trans('toasts.messageSent'));
        }, trans('main.errorMessage'));

        return $this->conrtollerJsonResponse($result);
    }
}
