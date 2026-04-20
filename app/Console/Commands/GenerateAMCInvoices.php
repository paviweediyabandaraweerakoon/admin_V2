<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Services\AMCInvoiceTableService;

/**
 * Command to generate AMC invoices for active projects with upcoming AMC dates.
 *
 * This command identifies projects that are active and have their next AMC date matching the current date.
 * It ensures that no duplicate invoices are generated for the same project on the same day.
 * The actual invoice creation logic is delegated to the AMCInvoiceTableService for better separation of concerns.
 */
class GenerateAMCInvoices extends Command
{
    protected $signature = 'amc:generate-invoices';
    protected $description = 'Identify active projects and generate automated AMC invoices';

    public function handle(AMCInvoiceTableService $amcService)
    {
        $this->info('AMC Invoice generation started...');

        $projects = Project::active()
            ->whereDate('next_amc_date', now()->toDateString())
            ->whereDoesntHave('amcInvoices', function ($query) {
                $query->whereDate('invoice_date', now()->toDateString());
            })
            ->get();

        if ($projects->isEmpty()) {
            $this->comment('No projects found for AMC generation today.');
            return 0;
        }

        foreach ($projects as $project) {
            try {
                //Use createAutomatedInvoice because we are passing a Project
                $amcService->createAutomatedInvoice($project); 
                $this->line("Generated for: {$project->project_name}");
            } catch (\Exception $e) {
                $this->error("Failed for Project ID {$project->id}: {$e->getMessage()}");
            }
        }

        $this->info('AMC Invoice generation completed successfully!');
        return 0;
    }
}