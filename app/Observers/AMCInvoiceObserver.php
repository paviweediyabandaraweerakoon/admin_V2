<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\AMCInvoice;
use App\Services\AMCInvoiceTableService;
use Illuminate\Support\Facades\Auth;

/**
 * Class AMCInvoiceObserver
 *
 * Observes AMCInvoice model events and delegates business logic to the service layer.
 */
class AMCInvoiceObserver
{
    /**
     * @param AMCInvoiceTableService $amcInvoiceService
     */
    public function __construct(
        protected AMCInvoiceTableService $amcInvoiceService
    ) {}

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
     * Handle the AMCInvoice "saving" event.
     */
    public function saving(AMCInvoice $amcInvoice): void
    {
        if (Auth::check()) {
            $amcInvoice->updated_by = Auth::id();
        }
    }

    /**
     * Handle the AMCInvoice "created" event.
     */
    public function created(AMCInvoice $amcInvoice): void
    {
        // Use service to update project next amc date
        $this->amcInvoiceService->updateProjectNextAmcDate($amcInvoice);
    }

    /**
     * Handle the AMCInvoice "updated" event.
     */
    public function updated(AMCInvoice $amcInvoice): void
    {
        // Only update if invoice_date was changed
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