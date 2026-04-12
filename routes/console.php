<?php

use App\Models\Project;
use App\Models\AMCInvoice;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Log::info("AMC Scheduler started running...");

     
    // check today's date with projects' next_amc_date and status active ,also check if there is no invoice already created for that project for today
    
    $projects = Project::where('status', 'active')
        ->whereDate('next_amc_date', now()->toDateString())
        ->whereDoesntHave('amcInvoices', function ($query) {
            $query->whereDate('invoice_date', now()->toDateString());
        })
        ->get();

    foreach ($projects as $project) {

    /**
     * Calculate AMC amount:
     * - Only applies if both initial value and AMC percentage are valid (> 0)
     * - Prevents invalid or zero-value calculations
     */

    $amount = 0;
        if ($project->initial_value > 0 && $project->amc_percentage > 0) {
            $amount = ($project->initial_value * ($project->amc_percentage / 100));
        }

    /**
     * Automatically generate AMC invoice for the project
     * - Unique invoice number is generated
     * - Due date is set to 14 days from invoice date
     * - Status is set to 'pending'
     */

        $invoice = AMCInvoice::create([
            'project_id'   => $project->id,
            'invoice_no'   => 'AUTO-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
            'amount'       => $amount,
            'description'  => "System Generated AMC Invoice for " . $project->project_name,
            'invoice_date' => now(),
            'due_date'     => now()->addDays(14),
            'status'       => AMCInvoice::STATUS_PENDING,
        ]);

        // The AMCInvoiceObserver will automatically update the project's next_amc_date

        Log::info("Automated AMC Invoice Created", ['project_id' => $project->id, 'invoice_no' => $invoice->invoice_no]);
    }
})->daily(); //can adjust the frequency as needed (e.g., hourly(), weekly(), etc.)