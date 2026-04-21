<?php

declare(strict_types=1);

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
        $this->info('--- AMC Automation Process Started ---');

        // 1. Send Reminders (7 days before)
        $this->info('Step 1: Checking for upcoming AMC reminders...');
        try {
            $amcService->sendUpcomingAMCReminders();
            $this->info('Reminders processed successfully.');
        } catch (Exception $e) {
            $this->error('Failed to send reminders: ' . $e->getMessage());
        }

        $this->line(''); 

        // 2. Generate Invoices (Due today)
        $this->info('Step 2: Checking for projects requiring invoice generation today...');
        
        $projects = Project::active()
            ->whereDate('next_amc_date', now()->toDateString())
            ->whereDoesntHave('amcInvoices', function ($query) {
                $query->whereDate('invoice_date', now()->toDateString());
            })
            ->get();

        if ($projects->isEmpty()) {
            $this->comment('No projects found for AMC generation today.');
        } else {
            foreach ($projects as $project) {
                try {
                    $amcService->createAutomatedInvoice($project);
                    $this->line("<info>Generated invoice for:</info> {$project->project_name}");
                } catch (Exception $e) {
                    $this->error("Failed for Project ID {$project->id}: {$e->getMessage()}");
                }
            }
        }

        $this->info('--- AMC Automation Process Completed ---');
        return 0;
    }
}