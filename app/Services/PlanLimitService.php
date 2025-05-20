<?php

namespace App\Services;

use App\Models\MyParent;
use App\Models\TeacherSubscription;

class PlanLimitService
{
    protected $subscription;
    protected $teacherId;

    public function __construct($teacherId)
    {
        $this->teacherId = $teacherId;
        $this->subscription = TeacherSubscription::where('teacher_id', $teacherId)
            ->where('status', 1) // Active
            ->where('end_date', '>=', now()) // Not expired
            ->whereHas('invoices', fn($q) => $q->subscription()->paid()) // Paid
            ->with('plan')
            ->firstOrFail();
    }

    public function canPerformAction(string $resource, int $count = 1): bool
    {
        $limitColumn = $this->getLimitColumn($resource);
        $limit = $this->subscription->plan->{$limitColumn};

        // Unlimited if limit is 0 or -1
        if ($limit <= 0) {
            return true;
        }

        $currentCount = $this->getCurrentCount($resource);
        return $currentCount + $count <= $limit;
    }

    public function hasFeature(string $feature): bool
    {
        $validFeatures = [
            'attendance_reports',
            'financial_reports',
            'performance_reports',
            'whatsapp_messages',
        ];

        if (!in_array($feature, $validFeatures)) {
            throw new \Exception("Invalid feature: $feature");
        }

        return $this->subscription->plan->{$feature} === true;
    }

    protected function getLimitColumn(string $resource): string
    {
        $periodMap = [
            1 => 'monthly', // Monthly
            2 => 'term',    // Term
            3 => 'year',    // Year
        ];
        $period = $periodMap[$this->subscription->period] ?? 'monthly';

        $map = [
            'students' => 'student_limit',
            'parents' => 'parent_limit',
            'assistants' => 'assistant_limit',
            'groups' => 'group_limit',
            'quizzes' => "quiz_{$period}_limit",
            'assignments' => "assignment_{$period}_limit",
            'resources' => "resource_{$period}_limit",
            'zooms' => "zoom_{$period}_limit",
        ];

        return $map[$resource] ?? throw new \Exception("Invalid resource: $resource");
    }

    protected function getCurrentCount(string $resource): int
    {
        $teacher = $this->subscription->teacher;

        return match ($resource) {
            'students' => $teacher->students()->count(),
            'parents' => MyParent::whereHas('students', fn($q) => $q
                ->whereNotNull('parent_id')
                ->whereHas('teachers', fn($t) => $t->where('teachers.id', $this->teacherId))
            )->count(),
            'assistants' => $teacher->assistants()->count(),
            'groups' => $teacher->groups()->count(),
            'quizzes' => $teacher->quizzes()->count(),
            'assignments' => $teacher->assignments()->count(),
            'resources' => $teacher->resources()->count(),
            'zooms' => $teacher->zooms()->count(),
            default => throw new \Exception("Invalid resource: $resource"),
        };
    }
}
