<?php


namespace App\Services;

use App\Models\Project;
use App\Models\AMCInvoice;
use App\Models\User;
use App\Notifications\UpcomingAMCNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Service class to handle AMC invoice table data and business logic.
 *
 */
class AMCInvoiceTableService
{
    /**
     * Get formatted AMC invoice data for DataTables.
     *
     * @param array $requestData
     * @return array
     */
    public function getTableData(array $requestData): array
    {
        $user = Auth::user();
        $search = $requestData['search']['value'] ?? null;
        $start = (int) ($requestData['start'] ?? 0);
        $length = (int) ($requestData['length'] ?? 10);

        $columns = ['id', 'invoice_no', 'project_id', 'amount', 'status', 'invoice_date', 'due_date'];
        $order_column = $columns[$requestData['order'][0]['column'] ?? 0] ?? 'id';
        $order_dir = $requestData['order'][0]['dir'] ?? 'desc';

        $query = AMCInvoice::with(['project.customer'])
            ->searchData($search,
                ['invoice_no', 'amount', 'status'],
                ['project' => ['project_name']]
            );

        $recordsTotal = AMCInvoice::count();
        $recordsFiltered = $query->count();

        $invoices = $query->tableData($order_column, $order_dir, $start, $length)->get();

        $data = [];
        foreach ($invoices as $invoice) {
            $url = "/amc-invoices/{$invoice->id}";
            $edit_btn = $user?->can('amc-invoices edit')
                ? "<i title='Edit' class='fas fa-edit mr-3 cursor-pointer text-primary amc-invoice-edit-btn' data-id='{$invoice->id}' data-url='{$url}' data-invoice-no='".e($invoice->invoice_no)."' data-project='{$invoice->project_id}' data-amount='{$invoice->amount}' data-status='{$invoice->status}' data-invoice-date='".($invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : "")."' data-due-date='".($invoice->due_date ? $invoice->due_date->format('Y-m-d') : "")."'></i>"
                : "";

            $delete_btn = $user?->can('amc-invoices delete')
                ? "<i title='Delete' class='fas fa-trash-alt cursor-pointer text-danger amc-invoice-delete-btn' data-id='{$invoice->id}' data-url='{$url}'></i>"
                : "";

            $statusBadge = match($invoice->status) {
                AMCInvoice::STATUS_PAID => '<span class="badge badge-success">Paid</span>',
                AMCInvoice::STATUS_CANCELLED => '<span class="badge badge-danger">Cancelled</span>',
                default => '<span class="badge badge-warning">Pending</span>'
            };

            $data[] = [
                e($invoice->invoice_no),
                e($invoice->project?->project_name ?? 'N/A'),
                e($invoice->project?->customer?->company_name ?? 'N/A'),
                number_format((float)$invoice->amount, 2),
                $statusBadge,
                $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '-',
                $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '-',
                $edit_btn . $delete_btn
            ];
        }
        return [
            "draw" => intval($requestData['draw'] ?? 0),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
    }

    public function processAmcAutomation(): void
    {
        $today = now()->toDateString();
        $sevenDaysLater = now()->addDays(7)->toDateString();
        
        // Fetch projects with next AMC date today or within the next 7 days
        $projects = Project::active()
        ->where(function ($query) use ($today, $sevenDaysLater) {
            $query->whereDate('next_amc_date', $today)
                    ->orWhereDate('next_amc_date', $sevenDaysLater);
        })
        ->get();
        
        $users = User::all(); 
        
        foreach ($projects as $project) {
            // Send notification to users about the upcoming AMC date
           // Notification::send($users, new UpcomingAMCNotification($project));
            
            if ($project->next_amc_date->toDateString() === $today) {
                $this->createAutomatedInvoice($project);
            }
        }
    }
    
    /**
     * Creates an invoice from a Project object.
     */
    public function createAutomatedInvoice(Project $project): void
    {
        $amount = ($project->initial_value > 0 && $project->amc_percentage > 0) 
            ? ($project->initial_value * ($project->amc_percentage / 100)) 
            : 0;

        AMCInvoice::create([
            'project_id'   => $project->id,
            'invoice_no'   => 'AUTO-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
            'amount'       => $amount,
            'description'  => "System Generated AMC Invoice for " . $project->project_name,
            'invoice_date' => now(),
            'due_date'     => now(),
            'status'       => AMCInvoice::STATUS_PENDING,
        ]);
    }
    
    /**
     * Update the project's next AMC date.
     *
     * @param AMCInvoice $amcInvoice
     * @return void
     */
    public function updateProjectNextAmcDate(AMCInvoice $amcInvoice): void
    {
        $project = $amcInvoice->project;

        if ($project && (($project->amc_durations_month ?? 0) > 0) && $amcInvoice->invoice_date) {
            $invoiceDate = Carbon::parse($amcInvoice->invoice_date);
            $nextAmcDate = $invoiceDate->copy()->addMonths((int) $project->amc_durations_month);

            $project->next_amc_date = $nextAmcDate->format('Y-m-d');
            
            $project->save();
        }
    }

    /**
     * Get active projects for the dropdown.
    */
    
    public function getActiveProjects()
    {
        return \App\Models\Project::active()->get();
        }
}