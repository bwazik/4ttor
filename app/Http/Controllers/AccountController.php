<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Coupon;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Services\SessionService;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfilePicRequest;
use App\Traits\DatabaseTransactionTrait;
use App\Services\Admin\FileUploadService;
use App\Http\Requests\PersonalDataRequest;
use App\Http\Requests\PasswordUpdateRequest;

class AccountController extends Controller
{
    use DatabaseTransactionTrait, ServiceResponseTrait;

    protected $profilePicService;
    protected $accountService;
    protected $sessionService;
    protected $guard;
    protected $mapping;
    protected $userId;
    protected $guardMappings = [
        'teacher' => [
            'model' => Teacher::class,
            'view_prefix' => 'teacher.account',
            'profile_pic_path' => 'teachers',
        ],
        'student' => [
            'model' => Student::class,
            'view_prefix' => 'student.account',
            'profile_pic_path' => 'students',
        ],
    ];

    public function __construct(FileUploadService $profilePicService, AccountService $accountService, SessionService $sessionService)
    {
        $this->profilePicService = $profilePicService;
        $this->accountService = $accountService;
        $this->sessionService = $sessionService;
        $this->guard = Auth::getDefaultDriver();
        $this->mapping = $this->guardMappings[$this->guard];
        $this->userId = Auth::guard($this->guard)->id();
    }

    public function editPersonalInfo()
    {
        $model = $this->mapping['model'];

        if ($this->guard === 'teacher') {
            $user = $model::query()->select('id', 'username', 'name', 'phone', 'email', 'subject_id', 'plan_id')->findOrFail($this->userId);

            $data = [
                'subjects' => Subject::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray(),
                'grades' => Grade::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray(),
            ];

            $gradeIds = $user->grades->pluck('id')->toArray();
            $user->grades = implode(',', $gradeIds);

            $plan = Plan::find($user->plan_id);
            $currentStudents = $user->students()->count();
            $currentGroups = $user->groups()->count();
            $data['remainingStudents'] = $plan ? max(0, $plan->student_limit - $currentStudents) : 0;
            $data['remainingGroups'] = $plan ? max(0, $plan->group_limit - $currentGroups) : 0;

            $data['teacher'] = $user;
        } elseif($this->guard === 'student') {
            $user = $model::query()->select('id', 'username', 'name', 'phone', 'email', 'gender', 'birth_date', 'grade_id', 'parent_id')->findOrFail($this->userId);

            $data = [
                'teachers' => $user->teachers->mapWithKeys(fn($teacher) => [$teacher->uuid => $teacher->name]),
                'groups' => $user->groups->mapWithKeys(fn($group) => [$group->uuid => $group->name . ' - ' . $group->teacher->name]),
            ];

            $groupIds = $user->groups->pluck('uuid')->toArray();
            $teacherIds = $user->teachers->pluck('uuid')->toArray();
            $user->groups = implode(',', $groupIds);
            $user->teachers = implode(',', $teacherIds);

            $data['student'] = $user;
        }

        return view("{$this->mapping['view_prefix']}.personal", compact('data'));
    }

    public function updateProfilePic(ProfilePicRequest $request)
    {
        $result = $this->profilePicService->updateProfilePic($request, $this->mapping['model'], $this->userId, $this->mapping['profile_pic_path']);

        return $this->conrtollerJsonResponse($result);
    }

    public function updatePersonalInfo(PersonalDataRequest $request)
    {
        $result = $this->accountService->updatePersonalInfo($this->guard, $this->userId, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function securityIndex()
    {
        $sessions = $this->sessionService->getUserSessions($this->guard, $this->userId);
        $devices = $this->sessionService->getUserDevices($this->guard, $this->userId);

        return view("{$this->mapping['view_prefix']}.security", compact('sessions', 'devices'));
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $result = $this->accountService->updatePassword($this->guard, $this->userId, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function getCoupons(Request $request)
    {
        $couponsQuery = Coupon::query()
            ->select('id', 'code', 'is_used', 'amount')
            ->where("{$this->guard}_id", $this->userId)
            ->used()
            ->whereNull($this->guard === 'teacher' ? 'student_id' : 'teacher_id');

        if ($request->ajax()) {
            return $this->accountService->getCouponsForDatatable($couponsQuery);
        }

        return view("{$this->mapping['view_prefix']}.coupons");
    }

    public function redeemCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|min:3|max:10|exists:coupons,code',
        ]);

        $result = $this->accountService->redeemCoupon($this->guard, $this->userId, $validated);

        return $this->conrtollerJsonResponse($result);
    }
}
