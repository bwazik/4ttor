<?php

namespace App\Http\Controllers\Api;

use App\Models\Fee;
use App\Models\Plan;
use App\Models\Group;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentFee;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\TeacherSubscription;
use App\Traits\PublicValidatesTrait;

class DataFetchController extends Controller
{
    use PublicValidatesTrait;

    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = auth('teacher')->check() ? auth('teacher')->id() : null;
    }


    public function getTeacherGroupsByGrade(...$args)
    {
        $isAdminContext = isAdmin() ? true : false;

        try {
            if ($isAdminContext) {
                [$teacherId, $gradeId] = $args;
                $effectiveTeacherId = $teacherId;
            } else {
                [$gradeId] = $args;
                $effectiveTeacherId = $this->teacherId;
            }

            if ($validationResult = $this->validateTeacherGrade($gradeId, $effectiveTeacherId)) {
                return $validationResult;
            }

            $query = Group::select('id', 'name', 'grade_id')
                ->where('teacher_id', $effectiveTeacherId)
                ->where('grade_id', $gradeId)
                ->with('grade:id,name');

            $groups = $query->orderBy($isAdminContext ? 'id' : 'grade_id')
                ->get()
                ->mapWithKeys(function ($group) {
                    $gradeName = $group->grade?->name ?? 'Unknown Grade';
                    return [$group->id => "{$group->name} - {$gradeName}"];
            });

            if ($groups->isEmpty()) {
                return $this->errorResponse(trans('teacher/errors.noGroupsForGrade'));
            }

            return response()->json(['status' => 'success', 'data' => $groups]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getStudentData($studentId)
    {
        $isAdminContext = isAdmin() ? true : false;
        $effectiveTeacherId = $isAdminContext ? null : $this->teacherId;

        try {
            $query = Student::with(['grade', 'parent']);

            // For future use: Constrain groups to teacher's own groups
            /*
            if (!$isAdminContext) {
                $query->with(['groups' => function ($query) use ($effectiveTeacherId) {
                    $query->where('teacher_id', $effectiveTeacherId);
                }]);
            } else {
                $query->with('groups');
            }
            */

            $student = $isAdminContext
                ? $query->findOrFail($studentId)
                : $query->uuid($studentId)->firstOrFail();

            if (!$isAdminContext) {
                $isValidStudent = $student->teachers()->where('teacher_id', $effectiveTeacherId)->exists();
                if (!$isValidStudent) {
                    return $this->errorResponse(trans('toasts.ownershipError'));
                }
            }

            $data =  [
                'name' => $student->name,
                'grade' => [
                    'name' => $student->grade->name,
                ],
                'phone' => $student->phone,
                'email' => $student->email ?? 'N/A',
                'parent' => [
                    'name' => $student->parent->name,
                    'phone' => $student->parent->phone,
                    'email' => $student->parent->email ?? 'N/A',
                ]
            ];

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getStudentFeesByStudent($studentId)
    {
        $isAdminContext = isAdmin() ? true : false;
        $effectiveTeacherId = $isAdminContext ? null : $this->teacherId;

        try {
            $query = Student::with(['grade']);

            $student = $isAdminContext
                ? $query->findOrFail($studentId)
                : $query->uuid($studentId)->firstOrFail();

            if (!$isAdminContext) {
                $isValidStudent = $student->teachers()->where('teacher_id', $effectiveTeacherId)->exists();
                if (!$isValidStudent) {
                    return $this->errorResponse(trans('toasts.ownershipError'));
                }
            }

            $studentGradeId = $student->grade_id ?? null;
            $teacherIds = $isAdminContext
                ? $student->teachers->pluck('id')->toArray()
                : [$effectiveTeacherId];

            $fees = Fee::with(['grade', 'teacher'])
                ->where('grade_id', $studentGradeId)
                ->whereIn('teacher_id', $teacherIds)
                ->get()
                ->mapWithKeys(function ($fee) use ($isAdminContext) {
                    $key = $isAdminContext ? $fee->id : $fee->uuid;
                    $feeName = $fee->name ?? 'N/A';
                    $gradeName = $fee->grade->name ?? 'N/A';
                    $teacherName = $fee->teacher->name ?? 'N/A';
                    return [$key => $isAdminContext
                        ? sprintf('%s - %s - %s', $feeName, $teacherName, $gradeName)
                        : sprintf('%s - %s', $feeName, $gradeName)];
                });

            if ($fees->isEmpty()) {
                return $this->errorResponse(trans('toasts.noFeesFound'));
            }

            return response()->json(['status' => 'success', 'data' => $fees]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getStudentRegisteredFeesByStudent($studentId)
    {
        $isAdminContext = isAdmin() ? true : false;
        $effectiveTeacherId = $isAdminContext ? null : $this->teacherId;

        try {
            $student = $isAdminContext
                ? Student::findOrFail($studentId)
                : Student::uuid($studentId)->firstOrFail();

            if (!$isAdminContext) {
                $isValidStudent = $student->teachers()->where('teacher_id', $effectiveTeacherId)->exists();
                if (!$isValidStudent) {
                    return $this->errorResponse(trans('toasts.ownershipError'));
                }
            }

            $studentFeesQuery = StudentFee::where('student_id', $student->id)
                ->with(['fee.grade', 'fee.teacher']);

            if (!$isAdminContext) {
                $studentFeesQuery->whereHas('fee', function ($query) use ($effectiveTeacherId) {
                    $query->where('teacher_id', $effectiveTeacherId);
                });
            }

            $studentFees  = $studentFeesQuery->get()
                ->mapWithKeys(function ($studentFee) use ($isAdminContext) {
                    $key = $isAdminContext ? $studentFee->id : $studentFee->uuid;
                    $feeName = $studentFee->fee->name ?? 'N/A';
                    $gradeName = $studentFee->fee->grade->name ?? 'N/A';
                    $teacherName = $studentFee->fee->teacher->name ?? 'N/A';
                    return [$key => $isAdminContext
                        ? sprintf('%s - %s - %s', $feeName, $teacherName, $gradeName)
                        : sprintf('%s - %s', $feeName, $gradeName)];
                });

            if ($studentFees->isEmpty()) {
                return $this->errorResponse(trans('toasts.noFeesFound'));
            }

            return response()->json(['status' => 'success', 'data' => $studentFees]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getFeeData($feeId)
    {
        $isAdminContext = isAdmin() ? true : false;
        $effectiveTeacherId = $isAdminContext ? null : $this->teacherId;

        try {
            $fee = $isAdminContext
                ? Fee::with(['grade'])->findOrFail($feeId)
                : Fee::with(['grade'])->uuid($feeId)->firstOrFail();

            if (!$isAdminContext) {
                if ($fee->teacher_id !== $effectiveTeacherId) {
                    return $this->errorResponse(trans('toasts.ownershipError'));
                }
            }

            $data = [
                'amount' => $fee->amount,
            ];

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getStudentFeeData($studentFeeId)
    {
        $isAdminContext = isAdmin() ? true : false;
        $effectiveTeacherId = $isAdminContext ? null : $this->teacherId;

        try {
            $studentFee = $isAdminContext
                ? StudentFee::with(['fee.teacher'])->findOrFail($studentFeeId)
                : StudentFee::with(['fee.teacher'])
                    ->uuid($studentFeeId)
                    ->whereHas('fee', function ($query) use ($effectiveTeacherId) {
                        $query->where('teacher_id', $effectiveTeacherId);
                    })
                    ->firstOrFail();

            if (!$isAdminContext) {
                if ($studentFee->fee->teacher_id !== $effectiveTeacherId) {
                    return $this->errorResponse(trans('toasts.ownershipError'));
                }
            }

            $data = [
                'fee' => [
                    'teacher' => [
                        'name' => $studentFee->fee->teacher->name,
                        'phone' => $studentFee->fee->teacher->phone,
                        'email' => $studentFee->fee->teacher->email ?? 'N/A',
                    ],
                    'amount' => $studentFee->fee->amount,
                ],
                'discount' => $studentFee->discount,
                'amount' => $studentFee->amount,
                'exempted_status' => $studentFee->is_exempted ? trans('main.exempted') : trans('main.notexempted'),
            ];

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getPlanData($feeId, $period = 1)
    {
        try {
            $plan = Plan::findOrFail($feeId);
            $amount = $plan->monthly_price;

            switch ($period) {
                case 1:
                    $amount = $plan->monthly_price;
                    break;
                case 2:
                    $amount = $plan->term_price;
                    break;
                case 3:
                    $amount = $plan->year_price;
                    break;
            }

            $data = [
                'amount' => $amount,
            ];

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getTeacherSubscriptionsByTeacher($teacherId)
    {
        try {
            $teacher = Teacher::findOrFail($teacherId);

            $teacherSubscriptionsQuery = TeacherSubscription::where('teacher_id', $teacher->id)
                ->with(['plan']);

            $teacherSubscriptions = $teacherSubscriptionsQuery->get()
                ->mapWithKeys(function ($teacherSubscription) {
                    $planName = $teacherSubscription->plan->name ?? 'N/A';
                    return [$teacherSubscription->id => $planName];
                });

            if ($teacherSubscriptions->isEmpty()) {
                return $this->errorResponse(trans('toasts.noSubscriptionsFound'));
            }

            return response()->json(['status' => 'success', 'data' => $teacherSubscriptions]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getTeacherData($teacherId)
    {
        try {
            $teacher = Teacher::findOrFail($teacherId);

            $data =  [
                'name' => $teacher->name,
                'phone' => $teacher->phone,
                'email' => $teacher->email ?? 'N/A',
            ];

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getTeacherSubscriptionData($teacherSubscriptionId)
    {
        try {
            $teacherSubscription = TeacherSubscription::with(['plan'])->findOrFail($teacherSubscriptionId);

            $data = [
                'plan' => [
                    'amount' => $teacherSubscription->amount,
                ],
                'amount' => $teacherSubscription->amount,
            ];

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }

    public function getGroupLessons($groupId)
    {
        $isAdminContext = isAdmin() ? true : false;
        $effectiveTeacherId = $isAdminContext ? null : $this->teacherId;

        try {
            $group = $isAdminContext
                ? Group::findOrFail($groupId)
                : Group::uuid($groupId)->firstOrFail();

            if (!$isAdminContext) {
                if ($group->teacher_id !== $effectiveTeacherId) {
                    return $this->errorResponse(trans('toasts.ownershipError'));
                }
            }

            $lessons = Lesson::where('group_id', $group->id)->get()
                ->mapWithKeys(function ($lesson) use ($isAdminContext) {
                    $key = $isAdminContext ? $lesson->id : $lesson->uuid;
                    return [$key => $lesson->title];
                });

            if ($lessons->isEmpty()) {
                return $this->errorResponse(trans('toasts.noLessonsFound'));
            }

            return response()->json(['status' => 'success', 'data' => $lessons]);
        } catch (\Exception $e) {
            return $this->productionErrorResponse($e);
        }
    }
}
