<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Teacher;
use App\Models\TeacherSubscription;

class Invoiceobserver
{
    public function created(Invoice $invoice): void
    {
        //
    }

    public function updated(Invoice $invoice): void
    {
        if ($invoice->type == 1 && $invoice->status == 2 && $invoice->wasChanged('status')) {
            $subscription = TeacherSubscription::find($invoice->subscription_id);
            if ($subscription && $subscription->status == 1) {
                Teacher::where('id', $subscription->teacher_id)
                    ->update(['plan_id' => $subscription->plan_id]);
            }
        }
    }

    public function deleted(Invoice $invoice): void
    {
        //
    }

    public function restored(Invoice $invoice): void
    {
        //
    }

    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
