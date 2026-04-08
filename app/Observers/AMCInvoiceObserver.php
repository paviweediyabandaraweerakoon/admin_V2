<?php

namespace App\Observers;

use App\Models\AMCInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class AMCInvoiceObserver
 *
 * Observes AMCInvoice model events to automatically set created_by and updated_by fields,
 * and to update the related project's next AMC date when an invoice is created or updated.
 */
class AMCInvoiceObserver
{
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
        $this->updateProjectNextAmcDate($amcInvoice);
    }

    /**
     * Handle the AMCInvoice "updated" event.
     */
    public function updated(AMCInvoice $amcInvoice): void
    {
        if ($amcInvoice->wasChanged('invoice_date')) {
            $this->updateProjectNextAmcDate($amcInvoice);
        }
    }

    /**
     * Handle the AMCInvoice "deleting" event.
     */
    public function deleting(AMCInvoice $amcInvoice): void
    {
        if (Auth::check()) {
            $amcInvoice->updated_by = Auth::id();
            $amcInvoice->saveQuietly();
        }
    }

    /**
     * Updates the project's next AMC date.
     */
    protected function updateProjectNextAmcDate(AMCInvoice $amcInvoice): void
    {
        $project = $amcInvoice->project;

        if ($project && $project->amc_durations_month > 0) {
            $invoiceDate = Carbon::parse($amcInvoice->invoice_date);
            $nextAmcDate = $invoiceDate->addMonths((int) $project->amc_durations_month);

            $project->update(['next_amc_date' => $nextAmcDate]);
        }
    }
}