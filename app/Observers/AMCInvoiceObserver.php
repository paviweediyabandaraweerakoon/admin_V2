<?php

namespace App\Observers;

use App\Models\AMCInvoice;
use Illuminate\Support\Facades\Auth;
use App\Services\AMCInvoiceTableService;

/**
 * Class AMCInvoiceObserver
 *
 * Observes AMCInvoice model events to automatically set created_by and updated_by fields.
 */
class AMCInvoiceObserver
{
    protected $amcInvoiceService;

    // Dependency injection of the AMCInvoiceTableService to handle business logic related to AMC invoices.
    public function __construct(AMCInvoiceTableService $amcInvoiceService)
    {
        $this->amcInvoiceService = $amcInvoiceService;
    }

    /**
     * Handle the AMCInvoice "saving" event.
     */
    public function saving(AMCInvoice $amcInvoice): void
    {
        if (Auth::check()) {
            $amcInvoice->updated_by = Auth::id();
        }
    }

    /**
     * Handle the AMCInvoice "creating" event.
     */
    public function creating(AMCInvoice $amcInvoice): void
    {
        if (Auth::check()) {
            $amcInvoice->created_by = Auth::id();
        }
    }

    /**
     * Handle the AMCInvoice "created" event.
     */
    public function created(AMCInvoice $amcInvoice): void
    {

        $this->amcInvoiceService->updateProjectNextAmcDate($amcInvoice);
    }

    /**
     * Handle the AMCInvoice "updated" event.
     */
    public function updated(AMCInvoice $amcInvoice): void
    {
        if ($amcInvoice->wasChanged('invoice_date')) {
        
            $this->amcInvoiceService->updateProjectNextAmcDate($amcInvoice);
        }
    }

    /**
     * Handle the AMCInvoice "deleting" event.
     */
    public function deleting(AMCInvoice $amcInvoice): void
    {
        if (Auth::check()) {
            $amcInvoice->updated_by = Auth::id();
            $amcInvoice->save();
        }
    }
}