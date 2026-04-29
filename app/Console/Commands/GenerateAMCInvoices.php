<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Services\AMCInvoiceTableService;
use Exception;

/**
 * Class GenerateAMCInvoices
 *
 * This command handles the automated process of sending AMC reminders
 * and generating invoices for due projects.
 */
class GenerateAMCInvoices extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'amc:generate-invoices';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Process AMC reminders (7 days before) and generate today\'s invoices';

    /**
     * Execute the console command.
     */
    public function handle(AMCInvoiceTableService $amcService): int
    {
        $this->info('--- AMC Automation Process Started at ' . now()->toDateTimeString() . ' ---');
        try {
            // Send reminders for projects with AMC due in 7 days and today
            $amcService->processAmcAutomation();

            $this->info('AMC Reminders and Invoices processed successfully.');
        } catch (Exception $e) {
            $this->error('Automation failed: ' . $e->getMessage());
            return 1; // Return non-zero exit code on failure
        }
        $this->info('--- AMC Automation Process Completed at ' . now()->toDateTimeString() . ' ---');
        return 0; // Return zero exit code on success
    }
}