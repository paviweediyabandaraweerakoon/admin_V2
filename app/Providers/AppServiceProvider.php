<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\AMCInvoice;
use App\Observers\AMCInvoiceObserver;
use App\Models\Project;
use App\Observers\ProjectObserver;
use App\Models\Customer;
use App\Observers\CustomerObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(125);

        // Register the AMCInvoice observer
        AMCInvoice::observe(AMCInvoiceObserver::class);

        // Register the Project observer
        Project::observe(ProjectObserver::class);

            // Register the Customer observer
        Customer::observe(CustomerObserver::class);

    }
}
