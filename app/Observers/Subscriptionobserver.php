<?php

namespace App\Observers;

use App\Models\Teacher;
use App\Models\TeacherSubscription;

class Subscriptionobserver
{
    /**
     * Handle the TeacherSubscription "created" event.
     */
    public function created(TeacherSubscription $teacherSubscription): void
    {
        //
    }

    /**
     * Handle the TeacherSubscription "updated" event.
     */
    public function updated(TeacherSubscription $subscription): void
    {
        if ($subscription->wasChanged('status') && $subscription->status == 2) {
            Teacher::where('id', $subscription->teacher_id)
                ->update(['plan_id' => null]);
        }
    }

    /**
     * Handle the TeacherSubscription "deleted" event.
     */
    public function deleted(TeacherSubscription $teacherSubscription): void
    {
        //
    }

    /**
     * Handle the TeacherSubscription "restored" event.
     */
    public function restored(TeacherSubscription $teacherSubscription): void
    {
        //
    }

    /**
     * Handle the TeacherSubscription "force deleted" event.
     */
    public function forceDeleted(TeacherSubscription $teacherSubscription): void
    {
        //
    }
}
