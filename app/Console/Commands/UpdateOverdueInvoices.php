<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class UpdateOverdueInvoices extends Command
{
    protected $signature = 'app:update-overdue-invoices';
    protected $description = 'Update invoices to overdue status if due date is past';

    public function handle()
    {
        $today = now()->startOfDay()->toDateString();

        $invoices = Invoice::where('status', 1)
            ->where('due_date', '<', $today)
            ->get();

        foreach ($invoices as $invoice) {
            $invoice->status = 3;
            $invoice->save();
        }

        $this->info("Updated {$invoices->count()} invoices to overdue status.");
    }
}
