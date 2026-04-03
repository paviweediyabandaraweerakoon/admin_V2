<?php

namespace App\Observers;

use App\Models\AMCInvoice;
use Carbon\Carbon;

/**
 * AMCInvoiceObserver
 *
 * Observes the AMCInvoice model events to perform actions such as updating the next AMC date on invoice creation.
 */

class AMCInvoiceObserver
{
    public function created(AMCInvoice $amcInvoice): void
    {
        $project = $amcInvoice->project;

        if ($project && $project->amc_durations_month > 0) {
            // Calculate the next AMC date based on the invoice date and the AMC duration in months
            $newNextDate = Carbon::parse($amcInvoice->invoice_date)
                                ->addMonths((int)$project->amc_durations_month);

            $project->update([
                'next_amc_date' => $newNextDate
            ]);
        }
    }
}