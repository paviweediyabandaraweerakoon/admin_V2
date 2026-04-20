<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AMCInvoiceTableService;

/**
 * Command to send email reminders for upcoming AMC dates.
 *
 * This command identifies projects with AMC dates approaching in the next 7 days and sends email notifications
 * to the relevant users. The actual reminder logic is handled by the AMCInvoiceTableService for better separation of concerns.
 */

class SendAMCReminders extends Command
{
    protected $signature = 'amc:send-reminders';
    protected $description = 'Send email reminders for upcoming AMC dates';

    public function handle(AMCInvoiceTableService $amcService)
    {
        $amcService->sendUpcomingAMCReminders();
        $this->info('AMC Reminders sent successfully!');
    }
}