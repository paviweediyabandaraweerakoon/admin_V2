<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerObserver
{
    /**
     * Before save to database when creating a new customer.
     */
    public function creating(Customer $customer): void
    {
       $customer->created_by = Auth::id();
    }

    /**
     * Before save to database when updating an existing customer.
     */
    public function updating(Customer $customer): void
    {
        $customer->updated_by = Auth::id();
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
