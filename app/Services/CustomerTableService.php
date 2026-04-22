<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

/**
 * Service class to handle customer table data retrieval and formatting for DataTables.
 */
class CustomerTableService
{
    /**
     * Delete a customer record.
     */
    public function deleteCustomer(Customer $customer): bool
    {
        return (bool) $customer->delete();
    }

    /**
     * Get formatted customer data for DataTables.
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

        $columns = ['id', 'company_name', 'phone', 'country', 'status', 'created_at'];
        $order_column = $columns[$requestData['order'][0]['column'] ?? 0] ?? 'id';
        $order_dir = $requestData['order'][0]['dir'] ?? 'desc';

        $query = Customer::query()
            ->searchData($search, ['company_name', 'phone', 'country', 'status']);

        $recordsTotal = Customer::count();
        $recordsFiltered = $query->count();

        $customers = $query->tableData($order_column, $order_dir, $start, $length)->get();

        $data = [];
        foreach ($customers as $customer) {
            $url = "/customers/{$customer->id}";

            // Edit Button - only show if user has edit permission
            $edit_btn = $user?->can('customers edit')
                ? "<a href='javascript:void(0)' class='customer-edit-btn text-primary py-0 px-1'
                    data-id='{$customer->id}'
                    data-company_name='".e($customer->company_name)."'
                    data-phone='".e($customer->phone)."' 
                    data-status='{$customer->status}'>
                    <i class='far fa-edit tx-16'></i>
                </a>"
                : "";

            // Delete Button - only show if user has delete permission
            $delete_btn = $user?->can('customers delete')
                ? "<a href='javascript:void(0)' class='customer-delete-btn text-danger py-0 px-1 mg-l-5'
                    data-id='{$customer->id}' 
                    data-url='{$url}' 
                    data-name='".e($customer->company_name)."'>
                    <i class='far fa-trash-alt tx-16'></i>
                </a>"
                : "";

            // formatting data array for DataTables
            $data[] = [
                e($customer->company_name),
                e($customer->phone),
                e($customer->country),
                $customer->status
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>',
                $customer->created_at->format('Y-m-d H:i'),
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