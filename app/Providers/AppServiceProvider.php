<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Observers\Invoiceobserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Invoice::observe(Invoiceobserver::class);
    }
}
