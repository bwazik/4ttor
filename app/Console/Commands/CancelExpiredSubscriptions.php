<?php

namespace App\Console\Commands;

use App\Models\Teacher;
use Illuminate\Console\Command;
use App\Models\TeacherSubscription;

class CancelExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:cancel-expired';
    protected $description = 'Cancel teacher subscriptions with past end dates';

    public function handle()
    {
        $subscriptions = TeacherSubscription::where('status', 1)
            ->where('end_date', '<', now())
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No expired subscriptions found.');
            return;
        }

        foreach ($subscriptions as $subscription) {
            $subscription->update(['status' => 3]);
            Teacher::where('id', $subscription->teacher_id)->update(['plan_id' => null]);
        }

        $this->info("Canceled {$subscriptions->count()} expired subscriptions.");
    }
}
