<?php

namespace App\Services;

use App\Models\Project;
use App\Models\AMCInvoice;
use Illuminate\Support\Facades\Auth;

/**
 * Service class to handle AMC invoice table data retrieval and formatting for DataTables.
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
}