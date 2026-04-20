<?php

namespace App\Services;

use App\Models\Project;
use App\Models\AMCInvoice;
use App\Notifications\UpcomingAMCNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Service class to handle AMC invoice table data retrieval and formatting for DataTables.
 *
 * Also contains business helpers related to AMC invoices (kept out of observers/controllers).
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

        // Eager load relationships and apply the search trait
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

    /**
     * Send email reminders for projects with AMC due in 7 days.
     */
    public function sendUpcomingAMCReminders(): void
    {
        $reminderDate = now()->addDays(7)->toDateString();
        
        $projects = Project::active()
            ->whereDate('next_amc_date', $reminderDate)
            ->get();

        if ($projects->isNotEmpty()) {
            $users = User::all(); // Get all users to notify
            foreach ($projects as $project) {
                Notification::send($users, new UpcomingAMCNotification($project));
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
            'due_date'     => now()->addDays(14),
            'status'       => AMCInvoice::STATUS_PENDING,
        ]);
        
        // Observer automatically handles next_amc_date update when this is created.
    }

    /**
     * Update the project's next AMC date using the invoice date and the project's AMC duration.
     *
     * If the project exists and has a positive amc_durations_month value and the invoice has an invoice_date,
     * this method calculates the next AMC date by adding the configured months to the invoice date and
     * persists the next_amc_date on the related project.
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

            // Persist in Y-m-d format (adjust if your column expects a Carbon/Date object)
            $project->next_amc_date = $nextAmcDate->format('Y-m-d');
            $project->save();
        }
    }
}